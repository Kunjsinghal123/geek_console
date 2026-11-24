<?php

namespace App\Services;

use App\Models\GoogleAccount;
use Google_Client;
use Google\Service\SearchConsole;
use Google\Service\SiteVerification;
use Google\Service\SiteVerification\SiteVerificationWebResourceGettokenRequest;
use Google\Service\SiteVerification\SiteVerificationWebResourceResource;
// use Google\Service\SiteVerification\SiteVerificationWebResourceResource\SiteData;
use Illuminate\Support\Facades\Log;

use Google\Service\SiteVerification\SiteVerificationWebResourceGettokenRequestSite;


// Added For Meta Verify 
use Google\Service\SiteVerification\SiteVerificationWebResourceResourceSite;

class GoogleSearchConsoleService
{
    /**
     * Get authenticated Google client for a store.
     */
    public function client(int $storeId): Google_Client
    {
        $account = GoogleAccount::where('store_id', $storeId)
            ->where('is_connected', true)
            ->first();

        if (!$account) {
            throw new \Exception('Google account is not connected.');
        }

        $client = new Google_Client();

        $client->setClientId(config('google.client_id'));
        $client->setClientSecret(config('google.client_secret'));
        $client->setAccessType('offline');

        /*
         * Decode the token saved during OAuth.
         */
        $accessToken = json_decode(
            $account->access_token,
            true
        );

        if (!$accessToken) {
            throw new \Exception('Invalid Google access token.');
        }

        $client->setAccessToken($accessToken);

        /*
         * Refresh access token if expired.
         */
        if ($client->isAccessTokenExpired()) {

            if (!$account->refresh_token) {
                throw new \Exception(
                    'Google refresh token is missing. Please reconnect Google.'
                );
            }

            $newToken = $client->fetchAccessTokenWithRefreshToken(
                $account->refresh_token
            );

            if (isset($newToken['error'])) {
                throw new \Exception(
                    $newToken['error_description']
                    ?? $newToken['error']
                );
            }

            /*
             * Preserve refresh token because Google may not
             * return it again during refresh.
             */
            if (empty($newToken['refresh_token'])) {
                $newToken['refresh_token'] = $account->refresh_token;
            }

            $account->update([
                'access_token' => json_encode($newToken),

                'refresh_token' => $newToken['refresh_token'],

                'token_expires_at' => now()->addSeconds(
                    $newToken['expires_in'] ?? 3600
                ),
            ]);

            $client->setAccessToken($newToken);
        }

        return $client;
    }

    /**
     * Search Console service.
     */
    public function searchConsole(int $storeId): SearchConsole
    {
        return new SearchConsole(
            $this->client($storeId)
        );
    }

    /**
     * Site Verification service.
     */
    public function siteVerification(int $storeId): SiteVerification
    {
        return new SiteVerification(
            $this->client($storeId)
        );
    }

    /**
     * Generate META verification token.
     *
     * $siteUrl example:
     * https://example.com/
     */
    // public function generateMetaVerificationToken(
    //     int $storeId,
    //     string $siteUrl
    // ): array {

    //     $service = $this->siteVerification($storeId);

    //     $siteData = new SiteData();

    //     $siteData->setType('SITE');
    //     $siteData->setIdentifier($siteUrl);

    //     $request = new SiteVerificationWebResourceGettokenRequest();

    //     $request->setVerificationMethod('META');
    //     $request->setSite($siteData);

    //     $response = $service
    //         ->webResource
    //         ->getToken($request)
    //         ->execute();

    //     return [
    //         'method' => $response->getMethod(),
    //         'token' => $response->getToken(),
    //     ];
    // }


    // public function generateMetaVerificationToken(
    // int $storeId,
    // string $siteUrl
    // ): array {
    //     $service = $this->siteVerification($storeId);

    //     if (empty($siteUrl)) {
    //         throw new \InvalidArgumentException(
    //             'Site URL cannot be empty.'
    //         );
    //     }

    //     $siteData = new SiteData();

    //     $siteData->setType('SITE');
    //     $siteData->setIdentifier($siteUrl);

    //     $request = new SiteVerificationWebResourceGettokenRequest();

    //     $request->setVerificationMethod('META');
    //     $request->setSite($siteData);

    //     $response = $service
    //         ->webResource
    //         ->getToken($request)
    //         ->execute();

    //     if (!$response) {
    //         throw new \RuntimeException(
    //             'Google returned an empty verification response.'
    //         );
    //     }

    //     $token = $response->getToken();

    //     if (empty($token)) {
    //         throw new \RuntimeException(
    //             'Google did not return a verification token.'
    //         );
    //     }

    //     return [
    //         'method' => $response->getMethod(),
    //         'token' => $token,
    //     ];
    // }
    // public function generateMetaVerificationToken(
    // int $storeId,
    // string $siteUrl
    // ): array {
    //     $service = $this->siteVerification($storeId);

    //     $site = new SiteVerificationWebResourceGettokenRequestSite();

    //     $site->setType('SITE');
    //     $site->setIdentifier($siteUrl);

    //     $request = new SiteVerificationWebResourceGettokenRequest();

    //     $request->setVerificationMethod('META');
    //     $request->setSite($site);

    //     // $response = $service->webResource->getToken($request)->execute();

    //     $webResource = $siteVerification->webResource();

    //     $response = $webResource->getToken($request)->execute();

    //     $token = $response->getToken();




    //     Log::info('GOOGLE TOKEN DEBUG', [
    //         'client_scopes' => $client->getScopes(),
    //         'token_scope' => $client->getAccessToken()['scope'] ?? null,
    //         'has_access_token' => !empty($client->getAccessToken()['access_token']),
    //     ]);



    //     if (!$response) {
    //         throw new \RuntimeException(
    //             'Google returned an empty verification response.'
    //         );
    //     }

    //     $token = $response->getToken();

    //     //         $token = $client->fetchAccessTokenWithAuthCode($code);

    //     // \Log::info('Google OAuth granted scopes', [
    //     //     'scope' => $token['scope'] ?? null,
    //     //     'has_refresh_token' => isset($token['refresh_token']),
    //     // ]);


    //     if (empty($token)) {
    //         throw new \RuntimeException(
    //             'Google did not return a verification token.'
    //         );
    //     }

    //     return [
    //         'method' => $response->getMethod(),
    //         'token' => $token,
    //     ];
    // }
    // public function generateMetaVerificationToken(
    // int $storeId,
    // string $siteUrl
    // ): array {
    //     $service = $this->siteVerification($storeId);

    //     $site = new SiteVerificationWebResourceGettokenRequestSite();

    //     $site->setType('SITE');
    //     $site->setIdentifier($siteUrl);

    //     $request = new SiteVerificationWebResourceGettokenRequest();

    //     $request->setVerificationMethod('META');
    //     $request->setSite($site);

    //     // Execute the getToken request
    //     $response = $service
    //         ->webResource()
    //         ->getToken($request)
    //         ->execute();

    //     if (!$response) {
    //         throw new \RuntimeException(
    //             'Google returned an empty verification response.'
    //         );
    //     }

    //     $token = $response->getToken();

    //     if (empty($token)) {
    //         throw new \RuntimeException(
    //             'Google did not return a verification token.'
    //         );
    //     }

    //     Log::info('Google verification token generated successfully', [
    //         'store_id' => $storeId,
    //         'site_url' => $siteUrl,
    //         'method' => $response->getMethod(),
    //     ]);

    //     return [
    //         'method' => $response->getMethod(),
    //         'token' => $token,
    //     ];
    // }

    // This is working function 
    public function generateMetaVerificationToken(int $storeId, string $siteUrl): array 
    {
        $service = $this->siteVerification($storeId);

        $site = new SiteVerificationWebResourceGettokenRequestSite();

        $site->setType('SITE');
        $site->setIdentifier($siteUrl);

        $request = new SiteVerificationWebResourceGettokenRequest();

        $request->setVerificationMethod('META');
        $request->setSite($site);

        // getToken() already returns the response in this Google PHP client version.
        $response = $service->webResource->getToken($request);

        if (!$response) {
            throw new \RuntimeException(
                'Google returned an empty verification response.'
            );
        }

        $token = $response->getToken();

        if (empty($token)) {
            throw new \RuntimeException(
                'Google did not return a verification token.'
            );
        }

        Log::info('Google verification token generated successfully', [
            'store_id' => $storeId,
            'site_url' => $siteUrl,
            'method' => $response->getMethod(),
        ]);

        return [
            'method' => $response->getMethod(),
            'token' => $token,
        ];
    }


    /**
     * Verify a META verified site.
     */
    // public function verifyMetaSite(int $storeId, string $siteUrl): array 
    // {
    //     $service = $this->siteVerification($storeId);

    //     $resource = new SiteVerificationWebResourceResource();

    //     $siteData = new SiteData();

    //     $siteData->setType('SITE');
    //     $siteData->setIdentifier($siteUrl);

    //     $resource->setSite($siteData);

    //     $response = $service
    //         ->webResource
    //         ->insert('META', $resource)
    //         ->execute();

    //     return [
    //         'id' => $response->getId(),
    //         'site' => $response->getSite(),
    //         'owners' => $response->getOwners(),
    //     ];
    // }

    public function verifyMetaSite(
    int $storeId,
    string $siteUrl
    ): array {

        $service = $this->siteVerification($storeId);

        $resource = new \Google\Service\SiteVerification\SiteVerificationWebResourceResource();

        $siteData = new \Google\Service\SiteVerification\SiteVerificationWebResourceResourceSite();

        $siteData->setType('SITE');
        $siteData->setIdentifier($siteUrl);

        $resource->setSite($siteData);

        $response = $service->webResource
            ->insert('META', $resource);

        return [
            'id' => $response->getId(),
            'site' => $response->getSite(),
            'owners' => $response->getOwners(),
        ];
    }

    // public function verifyMetaSite(
    // int $storeId,
    // string $siteUrl
    // ): array {

    //     $service = $this->siteVerification($storeId);

    //     $resource = new SiteVerificationWebResourceResource();

    //     $siteData = new SiteVerificationWebResourceResourceSite();

    //     $siteData->setType('SITE');
    //     $siteData->setIdentifier($siteUrl);

    //     $resource->setSite($siteData);

    //     $response = $service
    //         ->webResource
    //         ->insert('META', $resource)
    //         ->execute();

    //     return [
    //         'id' => $response->getId(),
    //         'site' => $response->getSite(),
    //         'owners' => $response->getOwners(),
    //     ];
    // }

    // public function verifyMetaSite(
    //     int $storeId,
    //     string $siteUrl
    // ): array {

    //     $service = $this->siteVerification($storeId);

    //     $resource = new \Google\Service\SiteVerification\SiteVerificationWebResourceResource();

    //     $siteData = new \Google\Service\SiteVerification\SiteVerificationWebResourceResourceSite();

    //     $siteData->setType('SITE');
    //     $siteData->setIdentifier($siteUrl);

    //     $resource->setSite($siteData);

    //     $response = $service->webResource
    //         ->insert('META', $resource);

    //     return [
    //         'id' => $response->getId(),
    //         'site' => $response->getSite(),
    //         'owners' => $response->getOwners(),
    //     ];
    // }


    /**
     * Add website to Search Console.
     */
    public function addSite(int $storeId, string $siteUrl): bool 
    {
        $service = $this->searchConsole($storeId);

        $service->sites
            ->add($siteUrl)
            ->execute();

        return true;
    }

    /**
     * Get Search Console sites.
     */
    public function sites(int $storeId)
    {
        $service = $this->searchConsole($storeId);

        return $service->sites
            ->listSites()
            ->getSiteEntry();
    }

    /**
     * Submit sitemap.
     */
    public function submitSitemap(
        int $storeId,
        string $siteUrl,
        string $sitemapUrl
    ): bool {

        $service = $this->searchConsole($storeId);

        $service->sitemaps
            ->submit(
                $siteUrl,
                $sitemapUrl
            )
            ->execute();

        return true;
    }

    /**
     * Get submitted sitemaps.
     */
    public function sitemaps(
        int $storeId,
        string $siteUrl
    ) {

        $service = $this->searchConsole($storeId);

        return $service->sitemaps
            ->listSitemaps($siteUrl)
            ->getSitemap();
    }

    /**
     * Search Console performance report.
     */
    public function searchAnalytics(
        int $storeId,
        string $siteUrl,
        string $startDate,
        string $endDate,
        array $dimensions = ['query'],
        int $rowLimit = 1000
    ): array {

        $service = $this->searchConsole($storeId);

        $request = new \Google\Service\SearchConsole\SearchAnalyticsQueryRequest();

        $request->setStartDate($startDate);
        $request->setEndDate($endDate);
        $request->setDimensions($dimensions);
        $request->setType('web');
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