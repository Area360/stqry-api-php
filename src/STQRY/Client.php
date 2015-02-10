<?php

namespace STQRY;

use OAuth2\AccessToken;
use OAuth2\Client as OAuth2Client;

class Client extends OAuth2Client
{
    const UA      = 'stqry-api-php';
    const VERSION = '1.0';
    const API_VERSION = '2.1';

    /** @var AccessToken */
    protected $token;

    /**
     * @param string        $clientId       Client ID as issued by STQRY
     * @param string|null   $clientSecret
     */
    public function __construct($clientId, $clientSecret = null)
    {
        $opts = [
            'site' => 'http://api.stqry.com',
            'token_url' => '/oauth',
        ];

        parent::__construct($clientId, $clientSecret, $opts);

        $this->connection->setDefaultOption('headers', [
                'User-Agent' => $this->getDefaultUserAgent(),
                'Accept' => 'application/vnd.stqry.*+json; version=' . self::API_VERSION,
            ]);
    }

    /**
     * Get the default User-Agent string to use with Guzzle
     *
     * @return string
     */
    protected  function getDefaultUserAgent()
    {
        $defaultAgent = self::UA . '/' . self::VERSION;

        if (extension_loaded('curl')) {
            $defaultAgent .= ' curl/' . curl_version()['version'];
        }

        $defaultAgent .= ' PHP/' . PHP_VERSION;

        return $defaultAgent;
    }

    /**
     * Override access token
     *
     * @param AccessToken $accessToken
     *
     * @return $this
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        $this->token = $accessToken;

        return $this;
    }

    /**
     * Obtain a new access token using STQRY email and password
     *
     * @param string $username
     * @param string $password
     *
     * @return AccessToken
     */
    public function getLoginToken($username, $password)
    {
        $params = [
            'grant_type' => 'stqry',
            'username' => $username,
            'password' => $password,
        ];

        $this->token = parent::getToken($params);

        return $this->token;
    }

    /**
     * Perform a GET request on a resource
     *
     * @param string|array $uri
     *
     * @throws \Exception
     * @return \OAuth2\Response
     */
    public function get($uri)
    {
        if (!$this->token) {
            throw new \Exception('Access token required before performing request.');
        }

        if (is_array($uri)) {
            $uris = [];

            foreach ($uri as $value) {
                $uris[] = urlencode($value);
            }

            $ids = implode(',', $uris);

            $uri = '/?ids=' . $ids;
        }

        return $this->token->request('GET', $uri);
    }

    public function getResponse($request, $parseMode = 'automatic')
    {
        $response = parent::getResponse($request, $parseMode);

        // Force set parse as JSON. Automatic checks for application/json, so fails with versioning
        $response->parseMode = 'json';

        return $response;
    }
}
