<?php

namespace App\Http\Controllers;

use App\Models\GoogleAccount;
use App\Models\Store;
use App\Services\GoogleService;
use Illuminate\Http\Request;
// New code for step 2 domain verfy
use App\Models\DomainVerification;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

// code end for domain verify

class GoogleController extends Controller
{
    protected $google;

    public function __construct(GoogleService $google)
    {
        $this->google = $google;
    }

    // 🔹 Step 1: Redirect to Google
    // public function redirect(Request $request)
    // {
    //     $client = $this->google->client();

    //     return redirect()->away($client->createAuthUrl());
    // }

    // 🔹 Step 2: Callback
    // public function callback(Request $request)
    // {
    //     if (!$request->has('code')) {
    //         dd('NO CODE RECEIVED', $request->all());
    //     }

    //     $client = $this->google->client();

    //     $token = $client->fetchAccessTokenWithAuthCode($request->code);

    //     if (!isset($token['access_token'])) {
    //         dd('TOKEN FAILED', $token);
    //     }

    //     if (isset($token['error'])) {
    //         return redirect()->back()->with('error', 'Google connection failed');
    //     }

    //     $client->setAccessToken($token);

    //     // Get user info
    //     $googleService = new \Google_Service_Oauth2($client);
    //     $googleUser = $googleService->userinfo->get();

    //     // Get current store (IMPORTANT: you should track store via session)
    //     $store = Store::where('store_domain', session('store'))->first();

    //     if (!$store) {
    //         dd('STORE NOT FOUND');
    //     }
    //     // Save data
    //     GoogleAccount::updateOrCreate(
    //         ['store_id' => $store->id],
    //         [
    //             'google_id' => $googleUser->id,
    //             'google_name' => $googleUser->name,
    //             'google_email' => $googleUser->email,
    //             'google_avatar' => $googleUser->picture,
    //             'access_token' => json_encode($token),
    //             'refresh_token' => $token['refresh_token'] ?? null,
    //             'token_expires_at' => now()->addSeconds($token['expires_in']),
    //             'is_connected' => true,
    //         ]
    //     );

    //     return redirect('/setup?step=2')->with('success', 'Google connected successfully');
    // }

    // public function callback(Request $request)
    // {
    //     if ($request->has('error')) {
    //         return redirect('/setup')->with('error', 'User denied access');
    //     }

    //     if (!$request->has('code')) {
    //         return redirect('/setup')->with('error', 'No code received');
    //     }

    //     $client = $this->google->client();

    //     $token = $client->fetchAccessTokenWithAuthCode($request->code);

    //     if (!isset($token['access_token'])) {
    //         dd('Token Error', $token);
    //     }

    //     // ✅ Proper way
    //     $client->setAccessToken([
    //         'access_token' => $token['access_token'],
    //         'expires_in' => $token['expires_in'],
    //         'created' => time(),
    //     ]);

    //     // ✅ Debug
    //     if (!$client->getAccessToken()) {
    //         dd('Access token not set properly');
    //     }

    //     // $googleService = new \Google_Service_Oauth2($client);
    //     // $googleUser = $googleService->userinfo->get();

    //     // // $store = Store::where('store_id', session('store'))->first();

    //     $store = 80032334062;

    //     // if (!$store) {
    //     //     dd('Store not found');
    //     // }

    //     // GoogleAccount::updateOrCreate(
    //     //     // ['store_id' => $store->id],
    //     //     ['store_id' => $store],
    //     //     [
    //     //         'google_id' => $googleUser->id,
    //     //         'google_name' => $googleUser->name,
    //     //         'google_email' => $googleUser->email,
    //     //         'google_avatar' => $googleUser->picture,
    //     //         'access_token' => json_encode($token),
    //     //         'refresh_token' => $token['refresh_token'] ?? null,
    //     //         'token_expires_at' => now()->addSeconds($token['expires_in']),
    //     //         'is_connected' => true,
    //     //     ]
    //     // );

    //     // Decode user info from id_token
    //     $payload = json_decode(base64_decode(explode('.', $token['id_token'])[1]), true);

    //     // $store = Store::where('store_domain', session('store'))->first();

    //     GoogleAccount::updateOrCreate(
    //         ['store_id' => $store],
    //         [
    //             'google_id' => $payload['sub'],
    //             'google_name' => $payload['name'],
    //             'google_email' => $payload['email'],
    //             'google_avatar' => $payload['picture'] ?? null,
    //             'access_token' => json_encode($token),
    //             'refresh_token' => $token['refresh_token'] ?? null,
    //             'token_expires_at' => now()->addSeconds($token['expires_in']),
    //             'is_connected' => true,
    //         ]
    //     );

    //     return redirect('/setup')->with('success', 'Google connected');
    // }


    public function redirect($store_id)
    {
        $store = Store::where('store_id', $store_id)->first();

        if (!$store) {
            return redirect('/setup-wizard')->with(
                'error',
                'Store not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Store context
        |--------------------------------------------------------------------------
        */

        session([
            'google_oauth_store_id' => $store->store_id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Google OAuth
        |--------------------------------------------------------------------------
        */

        $client = $this->google->client();

        return redirect()->away(
            $client->createAuthUrl()
        );
    }

    public function callback(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Google authorization error
        |--------------------------------------------------------------------------
        */

        if ($request->has('error')) {
            session()->forget('google_oauth_store_id');

            return redirect('/setup-wizard')->with(
                'error',
                'Google authorization was denied.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Authorization code
        |--------------------------------------------------------------------------
        */

        $code = $request->query('code');

        if (!$code) {
            session()->forget('google_oauth_store_id');

            return redirect('/setup-wizard')->with(
                'error',
                'No authorization code received from Google.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Shopify store context
        |--------------------------------------------------------------------------
        */

        $shopifyStoreId = session('google_oauth_store_id');

        if (!$shopifyStoreId) {
            return redirect('/setup-wizard')->with(
                'error',
                'Store context was lost. Please start Google connection again.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Find Shopify store
        |--------------------------------------------------------------------------
        */

        $store = Store::where('store_id', $shopifyStoreId)->first();

        if (!$store) {
            session()->forget('google_oauth_store_id');

            return redirect('/setup-wizard')->with(
                'error',
                'Shopify store not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Exchange Google code for token
        |--------------------------------------------------------------------------
        */

        $client = $this->google->client();

        $token = $client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            return redirect('/setup-wizard')->with(
                'error',
                'Google token exchange failed: ' .
                ($token['error_description'] ?? $token['error'])
            );
        }

        if (empty($token['access_token'])) {
            return redirect('/setup-wizard')->with(
                'error',
                'Google access token was not received.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Set token
        |--------------------------------------------------------------------------
        */

        $client->setAccessToken($token);

        /*
        |--------------------------------------------------------------------------
        | Get Google user
        |--------------------------------------------------------------------------
        */

        $oauth2 = new \Google\Service\Oauth2($client);

        $googleUser = $oauth2->userinfo->get();

        if (!$googleUser || !$googleUser->getId()) {
            return redirect('/setup-wizard')->with(
                'error',
                'Unable to retrieve Google account information.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Save Google account
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | $store->id       = Laravel stores.id
        | $store->store_id = Shopify shop ID
        |
        | google_accounts.store_id is a foreign key to stores.id.
        |
        */

        GoogleAccount::updateOrCreate(
            [
                'store_id' => $store->store_id,
            ],
            [
                'google_id' => $googleUser->getId(),
                'google_name' => $googleUser->getName(),
                'google_email' => $googleUser->getEmail(),
                'google_avatar' => $googleUser->getPicture(),

                'access_token' => json_encode($token),

                /*
                * Google may not return a refresh token
                * on every subsequent authorization.
                */
                'refresh_token' => $token['refresh_token'] ?? null,

                /* 
                * Do not destroy an existing refresh token 
                * if Google does not return one. 
                */ 
                // 'refresh_token' => $token['refresh_token'] ?? GoogleAccount::where('store_id', $store->store_id)->value('refresh_token'), 

                'token_expires_at' => now()->addSeconds(
                    $token['expires_in'] ?? 3600
                ),

                'is_connected' => true,

                
                
                
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Clear temporary OAuth context
        |--------------------------------------------------------------------------
        */

        session()->forget('google_oauth_store_id');

        /*
        |--------------------------------------------------------------------------
        | Return to setup wizard
        |--------------------------------------------------------------------------
        */

        return redirect()->route('setup-wizard', [
            'store_id' => $store->store_id,
        ])->with(
            'success',
            'Google connected successfully.'
        );
    }

    // new code for step 2 verify domain

    // public function generateMeta(Request $request)
    // {
    //     $store = Store::where('store_id', $request->store_id)->first();

    //     if (!$store) {
    //         return response()->json(['error' => 'Store not found'], 404);
    //     }

    //     $code = Str::random(40);

    //     // $store->verification_code = $code;
    //     // $store->save();

    //     return response()->json([
    //         'meta_tag' => '<meta name="google-site-verification" content="' . $code . '" />'
    //     ]);
    // }

    // public function injectMeta(Request $request)
    // {
    //     $store = Store::where('store_id', $request->store_id)->first();

    //     $themeId = $this->getMainThemeId($store);

    //     $assetKey = 'layout/theme.liquid';

    //     // $response = $this->shopifyRequest($store, 'GET',
    //     //     "/admin/api/2023-10/themes/$themeId/assets.json",
    //     //     // "/admin/api/2026-01/themes/$themeId.json",
    //     //     ['asset[key]' => $assetKey]);

    //     // $themeContent = $response['asset']['value'];

    //     $response = $this->shopifyRequest($store, 'GET',
    //         "/admin/api/2023-10/themes/{$themeId}/assets.json",
    //         ['asset[key]' => 'layout/theme.liquid']);

    //     $themeContent = $response['asset']['value'];

    //     // $response = $this->shopifyRequest($store, 'GET', '/admin/api/2023-10/shop.json');
    //     // dd($response);

    //     // $metaTag = '<meta name="google-site-verification" content="' . $store->verification_code . '" />';

    //     $metaTag = '<meta name="google-site-verification" content="8ZYMOPKiy7s7tvZ823NzY1VE6MEVA2LKAyHU0sk5" />';

    //     // Prevent duplicate
    //     if (!str_contains($themeContent, $metaTag)) {
    //         $updatedContent = preg_replace(
    //             '/<\/head>/i',
    //             $metaTag . "\n</head>",
    //             $themeContent,
    //             1  // only first occurrence
    //         );
    //     }

    //     $this->shopifyRequest($store, 'PUT',
    //         "/admin/api/2023-10/themes/{$themeId}/assets.json",
    //         [
    //             'asset' => [
    //                 'key' => 'layout/theme.liquid',
    //                 'value' => $updatedContent
    //             ]
    //         ]);

    //     // if (!str_contains($themeContent, $metaTag)) {
    //     //     $updatedContent = str_replace(
    //     //         '</head>',
    //     //         $metaTag . "\n</head>",
    //     //         $themeContent
    //     //     );

    //     //     $this->shopifyRequest($store, 'PUT',
    //     //         "/admin/api/2023-10/themes/$themeId/assets.json",
    //     //         [
    //     //             'asset' => [
    //     //                 'key' => $assetKey,
    //     //                 'value' => $updatedContent
    //     //             ]
    //     //         ]);
    //     // }

    //     return response()->json(['status' => 'meta injected']);
    // }

    public function generateMeta(Request $request)
    {
        $store = Store::where(
            'store_id',
            $request->store_id
        )->first();

        if (!$store) {
            return response()->json([
                'status' => 'error',
                'message' => 'Store not found'
            ], 404);
        }

        $googleAccount = GoogleAccount::where(
            'store_id',
            $store->store_id
        )->first();

        if (!$googleAccount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Google account is not connected.'
            ], 400);
        }

        /*
        * Your callback stores the complete Google OAuth
        * response as JSON.
        */
        $googleToken = json_decode(
            $googleAccount->access_token,
            true
        );

        if (
            !is_array($googleToken) ||
            empty($googleToken['access_token'])
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Google access token stored in database.'
            ], 400);
        }

        $accessToken = $googleToken['access_token'];

        $siteUrl = 'https://' . $store->store_domain . '/';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(
            'https://www.googleapis.com/siteVerification/v1/token',
            [
                'site' => [
                    'type' => 'SITE',
                    'identifier' => $siteUrl,
                ],
                'verificationMethod' => 'META',
            ]
        );

        if ($response->failed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Google failed to generate verification token.',
                'google_response' => $response->json(),
            ], $response->status());
        }

        $data = $response->json();

        if (empty($data['token'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Google did not return a verification token.',
                'google_response' => $data,
            ], 500);
        }

        $store->verification_code = $data['token'];
        $store->is_verified = false;
        $store->save();

        return response()->json([
            'status' => 'success',
            'token' => $data['token'],
            'meta_tag' =>
                '<meta name="google-site-verification" content="' .
                $data['token'] .
                '" />'
        ]);
    }


    public function injectMeta(Request $request)
    {
        $store = Store::where(
            'store_id',
            $request->store_id
        )->first();

        if (!$store) {
            return response()->json([
                'status' => 'error',
                'message' => 'Store not found'
            ], 404);
        }

        /*
        * Google token must already exist.
        */
        if (!$store->verification_code) {
            return response()->json([
                'status' => 'error',
                'message' => 'Google verification token has not been generated yet.'
            ], 400);
        }

        /*
        * Get main theme.
        */
        $themeId = $this->getMainThemeId($store);

        if (!$themeId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Main theme not found.'
            ], 404);
        }

        /*
        * Get theme.liquid
        */
        $response = $this->shopifyRequest(
            $store,
            'GET',
            "/admin/api/2026-01/themes/{$themeId}/assets.json",
            [
                'asset[key]' => 'layout/theme.liquid'
            ]
        );

        if (
            empty($response['asset']) ||
            !isset($response['asset']['value'])
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to read theme.liquid.'
            ], 500);
        }

        $themeContent = $response['asset']['value'];

        /*
        * Google verification meta tag.
        */
        $metaTag =
            '<meta name="google-site-verification" content="' .
            e($store->verification_code) .
            '" />';

        /*
        * Check whether the exact Google verification tag
        * already exists.
        */
        if (
            str_contains(
                $themeContent,
                'name="google-site-verification"'
            )
        ) {
            return response()->json([
                'status' => 'success',
                'message' => 'Google verification meta tag already exists.',
                'theme_id' => $themeId,
            ]);
        }

        /*
        * Make sure <head> exists.
        */
        if (!preg_match('/<\/head>/i', $themeContent)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not find </head> in theme.liquid.'
            ], 500);
        }

        /*
        * Insert before </head>
        */
        $updatedContent = preg_replace(
            '/<\/head>/i',
            $metaTag . "\n</head>",
            $themeContent,
            1
        );

        if (!$updatedContent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to modify theme.liquid.'
            ], 500);
        }

        /*
        * Write back to Shopify.
        */
        $this->shopifyRequest(
            $store,
            'PUT',
            "/admin/api/2026-01/themes/{$themeId}/assets.json",
            [
                'asset' => [
                    'key' => 'layout/theme.liquid',
                    'value' => $updatedContent
                ]
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Google verification meta tag injected.',
            'theme_id' => $themeId,
            'meta_tag' => $metaTag,
        ]);
    }



    

    // public function verifyDomain(Request $request)
    // {
    //     // $store = Store::where('store_id', $request->store_id)->first();
    //     $store = Store::where('store_id', '80032334062')->first();

    //     $url = 'https://' . $store->store_domain;

    //     $html = file_get_contents($url);

    //     // if (str_contains($html, $store->verification_code)) {
    //        // $store->is_verified = true;
    //        // $store->save();
    //     if (str_contains($html, '8ZYMOPKiy7s7tvZ823NzY1VE6MEVA2LKAyHU0sk5')) {
    //         return response()->json(['status' => 'verified']);
    //     }

    //     return response()->json(['status' => 'not_verified']);
    // }

    public function verifyDomain(Request $request)
    {
        // $store = Store::where('store_id', $request->store_id)->first();

        $store = Store::where('store_id', '80032334062')->first();

        $url = 'https://' . $store->store_domain;

        try {
            $client = new Client([
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'Accept' => 'text/html,application/xhtml+xml',
                ],
                'timeout' => 10,
            ]);

            $response = $client->get($url);

            $html = $response->getBody()->getContents();

            if (str_contains($html, 'password')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Store is password protected. Please disable it.'
                ]);
            }

            // if (str_contains($html, $store->verification_code)) {
            // $store->is_verified = true;
            // $store->save();
            if (str_contains($html, '8ZYMOPKiy7s7tvZ823NzY1VE6MEVA2LKAyHU0sk5')) {
                return response()->json(['status' => 'verified']);
            }

            return response()->json(['status' => 'not_verified']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // public function submitSitemap(Request $request)
    // {
    //     $store = Store::where('store_id', $request->store_id)->first();

    //     if (!$store->is_verified) {
    //         return response()->json(['error' => 'Domain not verified'], 403);
    //     }

    //     $siteUrl = 'https://' . $store->store_domain;
    //     $sitemapUrl = $siteUrl . '/sitemap.xml';

    //     $client = new \GuzzleHttp\Client();

    //     // Add site to Search Console
    //     $client->request('PUT',
    //         'https://www.googleapis.com/webmasters/v3/sites/' . urlencode($siteUrl),
    //         [
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . $store->google_access_token,
    //             ]
    //         ]);

    //     // Submit sitemap
    //     $client->request('PUT',
    //         'https://www.googleapis.com/webmasters/v3/sites/' . urlencode($siteUrl) . '/sitemaps/' . urlencode($sitemapUrl),
    //         [
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . $store->google_access_token,
    //             ]
    //         ]);

    //     $store->sitemap_submitted = true;
    //     $store->save();

    //     return response()->json(['status' => 'sitemap submitted']);
    // }

    public function submitSitemap(Request $request)
    {
        // $store = Store::where('store_id', $request->store_id)->first();
        $store = Store::where('store_id', '80032334062')->first();

        $googleAccount = GoogleAccount::where('store_id', '80032334062')->first();

        $accessToken = $googleAccount->access_token;

        $siteUrl = 'https://' . $store->store_domain;
        $sitemapUrl = $siteUrl . '/sitemap.xml';

        try {
            // Step 1: Add site
            Http::withToken($accessToken)
                ->put('https://www.googleapis.com/webmasters/v3/sites/' . urlencode($siteUrl));

            // Step 2: Submit sitemap
            $response = Http::withToken($accessToken)
                ->put('https://www.googleapis.com/webmasters/v3/sites/' . urlencode($siteUrl) . '/sitemaps/' . urlencode($sitemapUrl));

            return response()->json([
                'status' => 'success',
                'message' => 'Sitemap submitted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // private function getMainThemeId($store)
    // {
    //     $themes = $this->shopifyRequest($store, 'GET', '/admin/api/2026-01/themes.json');

    //     foreach ($themes['themes'] as $theme) {
    //         if ($theme['role'] == 'main') {
    //             // dd($theme['id']);
    //             return $theme['id'];
    //         }
    //     }

    //     return null;
    // }

    private function getMainThemeId($store)
    {
        $response = $this->shopifyRequest(
            $store,
            'GET',
            '/admin/api/2026-01/themes.json'
        );

        foreach ($response['themes'] ?? [] as $theme) {

            if (($theme['role'] ?? null) === 'main') {
                return $theme['id'];
            }
        }

        return null;
    }


    // private function shopifyRequest($store, $method, $endpoint, $data = [])
    // {
    //     $client = new \GuzzleHttp\Client();

    //     $url = "https://{$store->store_domain}{$endpoint}";

    //     $options = [
    //         'headers' => [
    //             'X-Shopify-Access-Token' => $store->access_token,
    //             'Content-Type' => 'application/json',
    //         ],
    //     ];

    //     if ($method == 'GET') {
    //         $options['query'] = $data;
    //     } else {
    //         $options['json'] = $data;
    //     }

    //     $response = $client->request($method, $url, $options);

    //     return json_decode($response->getBody(), true);
    // }

    // private function shopifyRequest($store, $method, $endpoint, $data = [])
    // {
    //     $client = new \GuzzleHttp\Client();

    //     $url = "https://{$store->store_domain}{$endpoint}";

    //     $options = [
    //         'headers' => [
    //             'X-Shopify-Access-Token' => $store->access_token,
    //             'Content-Type' => 'application/json',
    //         ],
    //     ];

    //     if ($method === 'GET') {
    //         $options['query'] = $data;
    //     } else {
    //         $options['body'] = json_encode($data);  // ✅ FIX HERE
    //     }

    //     try {
    //         $response = $client->request($method, $url, $options);

    //         return json_decode($response->getBody(), true);
    //     } catch (\GuzzleHttp\Exception\ClientException $e) {
    //         dd([
    //             'URL' => $url,
    //             'METHOD' => $method,
    //             'DATA' => $data,
    //             'ERROR' => $e->getResponse()->getBody()->getContents()
    //         ]);
    //     }
    // }


    private function shopifyRequest(
    $store,
    $method,
    $endpoint,
    $data = []
    ) {
        $client = new \GuzzleHttp\Client();

        $url = "https://{$store->store_domain}{$endpoint}";

        $options = [
            'headers' => [
                'X-Shopify-Access-Token' => $store->access_token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        if ($method === 'GET') {
            $options['query'] = $data;
        } else {
            $options['json'] = $data;
        }

        try {
            $response = $client->request(
                $method,
                $url,
                $options
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );

        } catch (\GuzzleHttp\Exception\ClientException $e) {

            $body = $e->getResponse()
                ? $e->getResponse()->getBody()->getContents()
                : null;

            throw new \RuntimeException(
                'Shopify API error: ' . $body
            );
        }
    }


    public function testThemeWrite()
    {
        $store = \App\Models\Store::where('store_id', '80032334062')->first();

        $themeId = 157990224110;

        try {
            $response = $this->shopifyRequest($store, 'PUT',
                "/admin/api/2023-10/themes/{$themeId}/assets.json",
                [
                    'asset' => [
                        'key' => 'assets/test.js',
                        'value' => "console.log('hello');"
                    ]
                ]);

            dd('SUCCESS', $response);
        } catch (\Exception $e) {
            dd('ERROR', $e->getMessage());
        }
    }
}
