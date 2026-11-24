<?php

// namespace App\Services;

// use Google_Client;

// class GoogleService
// {
//     public function client()
//     {
//         $client = new Google_Client();

//         $client->setClientId(config('google.client_id'));
//         $client->setClientSecret(config('google.client_secret'));
//         $client->setRedirectUri(config('google.redirect'));

//         $client->addScope([
//             'openid',
//             'email',
//             'profile',
//             'https://www.googleapis.com/auth/webmasters',
//             'https://www.googleapis.com/auth/analytics.readonly',
//         ]);

//         // $client->addScope([
//         //     'https://www.googleapis.com/auth/webmasters',  // Search Console
//         //     'https://www.googleapis.com/auth/analytics.readonly'
//         // ]);

//         $client->setAccessType('offline');  // for refresh token
//         $client->setPrompt('consent');

//         return $client;
//     }
// }


namespace App\Services;

use Google_Client;

class GoogleService
{
    public function client()
    {
        $client = new Google_Client();

        $client->setClientId(config('google.client_id'));
        $client->setClientSecret(config('google.client_secret'));
        $client->setRedirectUri(config('google.redirect'));

        $client->addScope([
            'openid',
            'email',
            'profile',

            // Google Search Console
            'https://www.googleapis.com/auth/webmasters',

            // Google Analytics
            'https://www.googleapis.com/auth/analytics.readonly',

            // Google Site Verification
            'https://www.googleapis.com/auth/siteverification',
        ]);

        $client->setAccessType('offline');

        /*
         * Always ask for consent so that Google can
         * return a refresh token during connection.
         */
        $client->setPrompt('consent');

        $client->setIncludeGrantedScopes(true);

        return $client;
    }
}
