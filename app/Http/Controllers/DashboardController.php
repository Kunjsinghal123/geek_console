
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index()
    {
        return view('pages.dashboard');
    }

    // ============================================
    // NEW FEATURE: GOOGLE SEARCH CONSOLE ANALYTICS
    // Merchant-specific analytics will be connected
    // through the authenticated Google account.
    // ============================================

    /**
     * Retrieve total clicks from Google Search Console.
     *
     * NOTE:
     * This endpoint will be connected to the dynamic
     * Google OAuth and Search Console service.
     * Hardcoded tokens and store URLs are prohibited.
     */
    public function totalClicks(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Google Search Console account connection is required.',
            'data' => null,
        ], 503);
    }
}
