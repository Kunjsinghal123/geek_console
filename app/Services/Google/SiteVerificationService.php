<?php

namespace App\Services\Google;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SiteVerificationService
{
    public function getMetaToken(
        string $accessToken,
        string $siteUrl
    ): string {
        $response = Http::withToken($accessToken)
            ->post(
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
            throw new RuntimeException(
                'Google token generation failed: ' .
                $response->body()
            );
        }

        $data = $response->json();

        if (empty($data['token'])) {
            throw new RuntimeException(
                'Google did not return a verification token.'
            );
        }

        return $data['token'];
    }
}
