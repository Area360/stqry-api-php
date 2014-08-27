<?php

namespace STQRY;

use OAuth2\AccessToken;
use OAuth2\Client as OAuth2Client;

class Client extends OAuth2Client
{
    /** @var AccessToken */
    protected $token;

    /**
     * @param string        $clientId       Client ID as issued by STQRY
     * @param string|null   $clientSecret
     */
    public function __construct($clientId, $clientSecret = null)
    {
        $opts = [
            'site' => 'http://api.stqryv2.mars.stqry.com',
            'token_url' => '/oauth',
        ];

        parent::__construct($clientId, $clientSecret, $opts);
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
     * @param string $uri
     *
     * @throws \Exception
     * @return \OAuth2\Response
     */
    public function get($uri)
    {
        if (!$this->token) {
            throw new \Exception('Access token required before performing request.');
        }

        return $this->token->request('GET', $uri);
    }
}
