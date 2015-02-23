<?php

/**
 * Instead of using the STQRY API PHP library, this example demonstrates how to use the STQRY API using only stripped
 * down cURL.
 *
 * It is for server-to-server requests only, and thus uses only the client ID and client secret, as opposed to user
 * specific 'login' access tokens.
 */

$url = 'http://api.stqry.com/entity/organization/cb3b66c097b5e53dc4316849fa7dd68f';

// Access credentials
$clientId     = '';
$clientSecret = '';

// Your company name or something to identify you
$userAgent = 'Acme Corporation';

$ch = curl_init();

curl_setopt_array(
    $ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        // User-Agent header
        CURLOPT_USERAGENT      => sprintf('%s curl/%s PHP/%s', $userAgent, curl_version()['version'], PHP_VERSION),
        // Authorization header (credentials as username/password)
        CURLOPT_USERPWD        => $clientId . ':' . $clientSecret,
        CURLOPT_HTTPHEADER     => [
            // Accept header, use version 2.1 of API
            'Accept: application/vnd.stqry.*+json; version=2.1',
        ],
    ]
);

$response = curl_exec($ch);

$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

// If the server came back with an error
if ($contentType == 'application/problem+json') {
    echo sprintf('Error (%s): %s %s', $data->title, $data->status, $data->detail) . PHP_EOL;

    exit;
}

$data = json_decode($response);

// Output the organization name and number of stories
var_dump($data->name, $data->story_count);

// If you wanted to fetch all the stories for this organization, perform another request using the 'story' rel link
var_dump($data->_links->story);
