<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store; 
use App\Models\GoogleSearchProperty; 
use App\Services\GoogleSearchConsoleService; 

use Illuminate\Support\Facades\Log;


class GoogleSearchConsoleController extends Controller
{
    public function __construct( protected GoogleSearchConsoleService $google ) 
    { 
        
    } 
    
    /** * Start verification. */ 
    public function generateVerificationToken($store_id) 
    { 
        $store = Store::where('store_id', $store_id)->first(); 
        
        if (!$store) 
        { 
            return back()->with( 'error', 'Store not found.' ); 
        } 
            
        /* * Example: * * https://example.com/ */ 
            
        $siteUrl = $this->storeSiteUrl($store); 
            
        try 
        { 
            // $result = $this->google ->generateMetaVerificationToken( $store->id, $siteUrl );
            
            // GoogleSearchProperty::updateOrCreate( 
            //     [ 
            //         'store_id' => $store->id, 
            //         'site_url' => $siteUrl, 
            //     ], 
            //     [ 
            //         'property_type' => 'SITE', 
            //         'verification_method' => 'META', 
            //         'verification_token' => $result['token'], 
            //         'is_verified' => false, 
            //     ] 
            // ); 
                
            // /* * At this point the Shopify Theme App Embed * must output the META tag. */ 
            // return back()->with( 'success', 'Google verification token generated.' ); 



            Log::info('Generating Google verification token', [
                'store_id' => $store->store_id,
                'site_url' => $siteUrl,
            ]);

            $result = $this->google->generateMetaVerificationToken(
                // $store->id,
                $store->store_id,
                $siteUrl
            );

            Log::info('Google verification token generated', [
                'store_id' => $store->store_id,
                'site_url' => $siteUrl,
                'result' => $result,
            ]);

            GoogleSearchProperty::updateOrCreate(
                [
                    'store_id' => $store->store_id,
                    'site_url' => $siteUrl,
                ],
                [
                    'property_type' => 'SITE',
                    'verification_method' => 'META',
                    'verification_token' => $result['token'],
                    'is_verified' => false,
                ]
            );

            Log::info('GoogleSearchProperty updated', [
                'store_id' => $store->store_id,
                'site_url' => $siteUrl,
            ]);

            return back()->with(
                'success',
                'Google verification token generated.'
            );
                
        } 
                
        catch (\Throwable $e) 
        { 
            // return back()->with( 'error', $e->getMessage() ); 


            Log::error('Google verification token generation failed', [
                'store_id' => $store->store_id,
                'site_url' => $siteUrl,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with(
                'error',
                'Google verification failed: ' . $e->getMessage()
            );
        } 
    }
                
    /** * Verify site after meta tag has been injected. */ 
    // public function verify($store_id) 
    // { 
    //     $store = Store::where('store_id', $store_id)->first(); 
        
    //     if (!$store) 
    //     {
    //         return back()->with( 'error', 'Store not found.' ); 
    //     } 
        
    //     $siteUrl = $this->storeSiteUrl($store); 
        
    //     $property = GoogleSearchProperty::where( 'store_id', $store->id ) ->where( 'site_url', $siteUrl ) ->first(); 
        
    //     if (!$property) 
    //     { 
    //         return back()->with( 'error', 'Verification token not found.' ); 
    //     } 
        
    //     try 
    //     { 
    //         $result = $this->google ->verifyMetaSite( $store->id, $siteUrl );

    //         $property->update(
    //             [ 
    //                 'is_verified' => true, 
    //                 'verified_at' => now(), 
    //             ]
    //         ); 
                
    //         /* * Now add the property to Search Console. */ 
    //         $this->google->addSite( $store->id, $siteUrl ); 
                
    //         $property->update([ 'is_added_to_search_console' => true, ]); 
                
    //         return back()->with( 'success', 'Google Search Console connected successfully.' );   
    //     } 

    //     catch (\Throwable $e)
    //     { 
    //         return back()->with( 'error', 'Verification failed: ' . $e->getMessage() ); 
    //     } 
    // } 

    public function verify($store_id)
    {
        $store = Store::where('store_id', $store_id)->first();

        if (!$store) {
            return back()->with('error', 'Store not found.');
        }

        $siteUrl = $this->storeSiteUrl($store);

        // GoogleSearchProperty.store_id is the local Store primary key.
        $property = GoogleSearchProperty::where('store_id', $store->id)
            ->where('site_url', $siteUrl)
            ->first();

        if (!$property) {
            return back()->with('error', 'Verification token not found.');
        }

        try {
            /*
            * IMPORTANT:
            * Google service methods use the Shopify store ID.
            * GoogleSearchProperty uses the local Store ID.
            */
            $result = $this->google->verifyMetaSite(
                $store->store_id,
                $siteUrl
            );

            $property->update([
                'is_verified' => true,
                'verified_at' => now(),
            ]);

            /*
            * Now add the property to Search Console.
            */
            $this->google->addSite(
                $store->store_id,
                $siteUrl
            );

            $property->update([
                'is_added_to_search_console' => true,
            ]);

            return back()->with(
                'success',
                'Google Search Console connected successfully.'
            );
        } catch (\Throwable $e) {
            Log::error('Google site verification failed', [
                'store_id' => $store->store_id,
                'local_store_id' => $store->id,
                'site_url' => $siteUrl,
                'message' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                'Verification failed: ' . $e->getMessage()
            );
        }
    }

    // public function verify($store_id)
    // {
    //     $store = Store::where('store_id', $store_id)->first();

    //     if (!$store) {
    //         return back()->with('error', 'Store not found.');
    //     }

    //     $siteUrl = $this->storeSiteUrl($store);

    //     $property = GoogleSearchProperty::where('store_id', $store->id)
    //         ->where('site_url', $siteUrl)
    //         ->first();

    //     if (!$property) {
    //         return back()->with('error', 'Verification token not found.');
    //     }

    //     try {
    //         Log::info('Starting Google site verification', [
    //             'store_id' => $store_id,
    //             'local_store_id' => $store->id,
    //             'site_url' => $siteUrl,
    //         ]);

    //         // IMPORTANT:
    //         // This only verifies the existing META token.
    //         // It does NOT generate a new token.
    //         $result = $this->google->verifyMetaSite(
    //             $store->id,
    //             $siteUrl
    //         );

    //         Log::info('Google site verification successful', [
    //             'store_id' => $store_id,
    //             'local_store_id' => $store->id,
    //             'site_url' => $siteUrl,
    //         ]);

    //         $property->update([
    //             'is_verified' => true,
    //             'verified_at' => now(),
    //         ]);

    //         // Only add to Search Console AFTER verification succeeds.
    //         $this->google->addSite(
    //             $store->id,
    //             $siteUrl
    //         );

    //         $property->update([
    //             'is_added_to_search_console' => true,
    //         ]);

    //         return back()->with(
    //             'success',
    //             'Google Search Console connected successfully.'
    //         );

    //     } catch (\Throwable $e) {

    //         Log::error('Google site verification failed', [
    //             'store_id' => $store_id,
    //             'local_store_id' => $store->id,
    //             'site_url' => $siteUrl,
    //             'message' => $e->getMessage(),
    //         ]);

    //         // VERY IMPORTANT:
    //         // Do not generate another token here.
    //         // Do not redirect to token-generation route.
    //         // Do not retry automatically.

    //         return back()->with(
    //             'error',
    //             'Verification failed: ' . $e->getMessage()
    //         );
    //     }
    // }

    // public function verify($store_id)
    // {
    //     $store = Store::where('store_id', $store_id)->first();

    //     if (!$store) {
    //         return back()->with('error', 'Store not found.');
    //     }

    //     $siteUrl = $this->storeSiteUrl($store);

    //     $property = GoogleSearchProperty::where('store_id', $store->id)
    //         ->where('site_url', $siteUrl)
    //         ->first();

    //     if (!$property) {
    //         return back()->with('error', 'Google Search Console property not found.');
    //     }

    //     if (empty($property->verification_token)) {
    //         return back()->with(
    //             'error',
    //             'Verification token has not been generated yet.'
    //         );
    //     }

    //     if ($property->is_verified) {
    //         return back()->with(
    //             'success',
    //             'This site is already verified with Google.'
    //         );
    //     }

    //     try {

    //         \Log::info('Starting Google site verification', [
    //             'store_id' => $store->store_id,
    //             'local_store_id' => $store->id,
    //             'site_url' => $siteUrl,
    //         ]);

    //         /*
    //         * IMPORTANT:
    //         * Do NOT generate a new token here.
    //         *
    //         * The token should already have been generated
    //         * and installed on Shopify.
    //         */

    //         $result = $this->google->verifyMetaSite(
    //             $store->id,
    //             $siteUrl
    //         );

    //         \Log::info('Google site verification successful', [
    //             'store_id' => $store->store_id,
    //             'local_store_id' => $store->id,
    //             'site_url' => $siteUrl,
    //             'result' => $result,
    //         ]);

    //         $property->update([
    //             'is_verified' => true,
    //             'verified_at' => now(),
    //         ]);

    //         /*
    //         * Add property to Search Console only after
    //         * successful ownership verification.
    //         */
    //         $this->google->addSite(
    //             $store->id,
    //             $siteUrl
    //         );

    //         $property->update([
    //             'is_added_to_search_console' => true,
    //         ]);

    //         return back()->with(
    //             'success',
    //             'Google Search Console connected successfully.'
    //         );

    //     } catch (\Throwable $e) {

    //         \Log::error('Google site verification failed', [
    //             'store_id' => $store->store_id,
    //             'local_store_id' => $store->id,
    //             'site_url' => $siteUrl,
    //             'message' => $e->getMessage(),
    //         ]);

    //         /*
    //         * DO NOT regenerate the token here.
    //         */

    //         return back()->with(
    //             'error',
    //             'Google verification failed: ' . $e->getMessage()
    //         );
    //     }
    // }


    // public function verify($store_id)
    // {
    //     $store = Store::where('store_id', $store_id)->first();

    //     if (!$store) {
    //         return back()->with('error', 'Store not found.');
    //     }

    //     $siteUrl = $this->storeSiteUrl($store);

    //     $property = GoogleSearchProperty::where('store_id', $store->id)
    //         ->where('site_url', $siteUrl)
    //         ->first();

    //     if (!$property) {
    //         return back()->with('error', 'Verification token not found.');
    //     }

    //     try {

    //         Log::info('Starting Google site verification', [
    //             'store_id' => $store_id,
    //             'local_store_id' => $store->id,
    //             'site_url' => $siteUrl,
    //         ]);

    //         // IMPORTANT:
    //         // Do NOT generate another token here.
    //         // The token should already be installed on Shopify.
    //         $result = $this->google->verifyMetaSite(
    //             $store->id,
    //             $siteUrl
    //         );

    //         Log::info('Google site verification successful', [
    //             'store_id' => $store_id,
    //             'local_store_id' => $store->id,
    //             'site_url' => $siteUrl,
    //             'result' => $result,
    //         ]);

    //         $property->update([
    //             'is_verified' => true,
    //             'verified_at' => now(),
    //         ]);

    //         // Add to Search Console only after verification succeeded.
    //         $this->google->addSite(
    //             $store->id,
    //             $siteUrl
    //         );

    //         $property->update([
    //             'is_added_to_search_console' => true,
    //         ]);

    //         return back()->with(
    //             'success',
    //             'Google Search Console connected successfully.'
    //         );

    //     } catch (\Throwable $e) {

    //         Log::error('Google site verification failed', [
    //             'store_id' => $store_id,
    //             'local_store_id' => $store->id,
    //             'site_url' => $siteUrl,
    //             'message' => $e->getMessage(),
    //         ]);

    //         return back()->with(
    //             'error',
    //             'Verification failed: ' . $e->getMessage()
    //         );
    //     }
    // }



                
    /** * Generate the Shopify storefront URL. */ 
    private function storeSiteUrl(Store $store): string 
    { 
        /* 
        * Adjust this according to your Store model. * 
        * Example: * store_domain = example.myshopify.com 
        */ 
        
        $domain = $store->store_domain; 
        
        if (!preg_match('/^https?:\/\//i', $domain)) 
        { $domain = 'https://' . $domain; } 
        
        return rtrim($domain, '/') . '/'; 
    }



    public function submitSitemap($store_id)
    {
        $store = Store::where('store_id', $store_id)->first();

        if (!$store) {
            return back()->with(
                'error',
                'Store not found.'
            );
        }

        $siteUrl = $this->storeSiteUrl($store);

        $sitemapUrl = rtrim($siteUrl, '/') . '/sitemap.xml';

        try {

            $this->google->submitSitemap(
                $store->id,
                $siteUrl,
                $sitemapUrl
            );

            return back()->with(
                'success',
                'Shopify sitemap submitted successfully.'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                'Sitemap submission failed: ' .
                $e->getMessage()
            );
        }
    }

    public function sitemapStatus($store_id) { $store = Store::where( 'store_id', $store_id )->firstOrFail(); $siteUrl = $this->storeSiteUrl($store); try { $sitemaps = $this->google->sitemaps( $store->id, $siteUrl ); return response()->json([ 'success' => true, 'sitemaps' => $sitemaps, ]); } catch (\Throwable $e) { return response()->json([ 'success' => false, 'message' => $e->getMessage(), ], 500); } }

    public function keywords(Request $request, $store_id) { $store = Store::where( 'store_id', $store_id )->firstOrFail(); $siteUrl = $this->storeSiteUrl($store); $startDate = $request->get( 'start_date', now()->subDays(28)->format('Y-m-d') ); $endDate = $request->get( 'end_date', now()->subDay()->format('Y-m-d') ); try { $rows = $this->google->searchAnalytics( $store->id, $siteUrl, $startDate, $endDate, ['query'], 1000 ); return response()->json([ 'success' => true, 'date' => [ 'start' => $startDate, 'end' => $endDate, ], 'data' => $rows, ]); } catch (\Throwable $e) { return response()->json([ 'success' => false, 'message' => $e->getMessage(), ], 500); } }
    

    public function searchAnalytics(
        int $storeId,
        string $siteUrl,
        string $startDate,
        string $endDate,
        array $dimensions = ['query'],
        int $rowLimit = 1000,
        string $type = 'web'
    ): array {

        $service = $this->searchConsole($storeId);

        $request = new \Google\Service\SearchConsole\SearchAnalyticsQueryRequest();

        $request->setStartDate($startDate);
        $request->setEndDate($endDate);

        $request->setDimensions($dimensions);

        /*
        * web
        * image
        * video
        * news
        * discover
        * googleNews
        */
        $request->setType($type);

        $request->setRowLimit($rowLimit);

        $response = $service
            ->searchanalytics
            ->query($siteUrl, $request)
            ->execute();

        $rows = [];

        foreach ($response->getRows() ?? [] as $row) {

            $rows[] = [
                'keys' => $row->getKeys(),
                'clicks' => $row->getClicks(),
                'impressions' => $row->getImpressions(),
                'ctr' => $row->getCtr(),
                'position' => $row->getPosition(),
            ];
        }

        return $rows;
    }

}
