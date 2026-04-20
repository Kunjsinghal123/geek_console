<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InstallController extends Controller
{
    public function install(Request $request)
    {
        $shop = $request->get('shop');

        $clientId = config('shopify.client_id');
        $scope = config('shopify.scopes');
        $redirectUri = route('callback');

        $state = bin2hex(random_bytes(16));

        session(['shopify_oauth_state' => $state]);

        $installUrl = "https://{$shop}/admin/oauth/authorize?" . http_build_query([
            'client_id' => $clientId,
            'scope' => $scope,
            'redirect_uri' => $redirectUri,
            'state' => $state,
        ]);

        return redirect()->away($installUrl);
    }

    public function callback(Request $request)
    {
        // Get query params
        $shop = $request->query('shop');
        $code = $request->query('code');
        $hmac = $request->query('hmac');
        $state = $request->query('state');

        // Get credentials
        $clientId = config('shopify.client_id');
        $clientSecret = config('shopify.client_secret');

        // Verify store
        if (!$shop) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'MISSING_SHOP',
                    'message' => 'The shop parameter is required.'
                ]
            ], 400);
        }

        $shop = strtolower(trim($shop));

        // Validate format using Shopify regex
        if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\-]*\.myshopify\.com$/', $shop)) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'INVALID_SHOP_DOMAIN',
                    'message' => 'The shop domain is invalid.'
                ]
            ], 400);
        }

        // Not OAuth request
        if (!$code) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'MISSING_CODE',
                    'message' => 'The authorization code is required.'
                ]
            ], 400);
        }

        // Verify HMAC
        if (!$hmac) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'MISSING_HMAC',
                    'message' => 'HMAC parameter is required.'
                ]
            ], 400);
        }

        $query = $request->query();
        unset($query['hmac']);

        ksort($query);

        $calculatedHmac = hash_hmac(
            'sha256',
            http_build_query($query),
            config('shopify.client_secret')
        );

        if (!hash_equals($hmac, $calculatedHmac)) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'INVALID_HMAC',
                    'message' => 'HMAC validation failed.'
                ]
            ], 403);
        }

        // Verify state
        if (!$state) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'MISSING_STATE',
                    'message' => 'State parameter is required.'
                ]
            ], 400);
        }

        if ($state !== session('shopify_oauth_state')) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'INVALID_STATE',
                    'message' => 'The state parameter is invalid or has expired.'
                ]
            ], 403);
        }

        session()->forget('shopify_oauth_state');

        // Exchange code for access token
        $response = Http::post("https://{$shop}/admin/oauth/access_token", [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SHOPIFY_API_ERROR',
                    'message' => 'Failed to communicate with Shopify API.'
                ]
            ], 500);
        }

        $data = $response->json();

        if (!isset($data['access_token'])) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'ACCESS_TOKEN_FAILED',
                    'message' => 'Failed to retrieve access token from Shopify.'
                ]
            ], 500);
        }

        $accessToken = $data['access_token'];
        $scope = $data['scope'] ?? null;

        // Get store details
        $shopData = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken
        ])->get("https://{$shop}/admin/api/2026-01/shop.json");

        if (!$shopData->successful()) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SHOPIFY_API_ERROR',
                    'message' => 'Failed to communicate with Shopify API.'
                ]
            ], 500);
        }

        $shopInfo = $shopData->json('shop');

        if (!$shopInfo) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SHOP_INFO_FAILED',
                    'message' => 'Failed to retrieve shop details.'
                ]
            ], 500);
        }

        // Get theme details
        $themes = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken
        ])->get("https://{$shop}/admin/api/2026-01/themes.json");

        // Get main theme
        $mainTheme = collect($themes->json('themes'))->firstWhere('role', 'main');

        // Get theme settings
        $themeSettings = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken
        ])->get("https://{$shop}/admin/api/2026-01/themes/{$mainTheme['id']}/assets.json", [
            'asset[key]' => 'config/settings_data.json'
        ]);

        $themeSettingsData = json_decode($themeSettings->json('asset.value'), true);

        // Extract logo from theme settings
        $storeLogo = $themeSettingsData['current']['logo'] ?? $themeSettingsData['current']['logo_image'] ?? null;

        // Convert to full URL
        $logoUrl = null;

        if ($storeLogo && str_contains($storeLogo, 'shopify://')) {
            $pathParts = explode('/', $storeLogo);
            $fileName = end($pathParts);

            $logoUrl = "https://{$shop}/cdn/shop/files/{$fileName}";
        }

        // Fallback to Shopify shop logo
        $logoUrl = $logoUrl ?? ($shopInfo['logo'] ?? null);

        // Save store details
        Store::updateOrCreate(
            ['store_id' => $shopInfo['id']],
            [
                'store_domain' => $shop,
                'store_name' => $shopInfo['name'] ?? null,
                'store_email' => $shopInfo['email'] ?? null,
                'store_logo' => $logoUrl,
                'owner_name' => $shopInfo['shop_owner'] ?? null,
                'access_token' => $accessToken,
                'scope' => $scope,
                'is_active' => true,
                'installed_at' => now(),
                'uninstalled_at' => null,
            ]
        );

        // Register uninstall webhook
        Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken
        ])->post("https://{$shop}/admin/api/2026-01/webhooks.json", [
            'webhook' => [
                'topic' => 'app/uninstalled',
                'address' => route('uninstall'),
                'format' => 'json'
            ]
        ]);

        // Redirect to setup-wizard
        return redirect()->route('setup-wizard', [
            'store_id' => $shopInfo['id'],
            'step' => 1
        ])->with('success', 'App installed successfully. Please complete the setup');
    }

    public function unInstall(Request $request)
    {
        // Get raw body
        $data = file_get_contents('php://input');

        $hmacHeader = $request->header('X-Shopify-Hmac-Sha256');

        $clientSecret = config('shopify.client_secret');

        // Verify webhook
        $calculatedHmac = base64_encode(
            hash_hmac('sha256', $data, $clientSecret, true)
        );

        if (!hash_equals($hmacHeader, $calculatedHmac)) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'INVALID_HMAC',
                    'message' => 'Webhook signature verification failed.'
                ]
            ], 401);
        }

        // Decode payload
        $payload = json_decode($data, true);

        $storeId = $payload['id'] ?? null;

        if (!$storeId) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'MISSING_STORE_ID',
                    'message' => 'Store ID not found in webhook payload.'
                ]
            ], 400);
        }

        // Update status
        Store::where('store_id', $storeId)->update([
            'is_active' => false,
            'access_token' => 'app_uninstalled',
            'uninstalled_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook processed successfully.'
        ], 200);
    }
}
