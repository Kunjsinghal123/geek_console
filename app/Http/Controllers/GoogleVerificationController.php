<?php

namespace App\Http\Controllers;

use App\Models\GoogleVerification;
use App\Models\Store;
use App\Services\Google\SiteVerificationService;
use Illuminate\Http\Request;

class GoogleVerificationController extends Controller
{
    public function generate(Request $request, SiteVerificationService $verificationService) 
    {
        /*
         * Replace this with however your existing
         * Shopify authentication resolves the shop.
         */
        $shop = $request->user();

        $siteUrl = 'https://' . $shop->shop_domain . '/';

        /*
         * Replace this with your existing Google OAuth
         * token retrieval logic.
         */
        $googleAccessToken = $shop->googleConnection->access_token;

        $token = $verificationService->getMetaToken(
            $googleAccessToken,
            $siteUrl
        );

        GoogleVerification::updateOrCreate(
            [
                'shop_id' => $shop->id,
                'site_url' => $siteUrl,
            ],
            [
                'verification_token' => $token,
                'verification_method' => 'META',
                'verified' => false,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Google verification token generated.',
        ]);
    }
}

