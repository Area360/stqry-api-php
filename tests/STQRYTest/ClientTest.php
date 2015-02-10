<?php

namespace STQRYTest;

use GuzzleHttp\Message\Response;
use OAuth2\AccessToken;
use STQRY\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    protected function setUp()
    {
        $this->client = new Client('');
        $this->email = '';
        $this->password = '';
    }

    public function testCanLoginWithClientCredentials()
    {
        $this->client = new Client('', '');

        $accessToken = $this->client->getClientCredentialsToken();

        $response = $this->client->get('');
    }

    public function testCanLogin()
    {
        $accessToken = $this->client->getLoginToken($this->email, $this->password);

        $this->assertInstanceOf('OAuth2\AccessToken', $accessToken);

        return $accessToken;
    }

    /**
     * @depends testCanLogin
     */
    public function testTokenDetails(AccessToken $accessToken)
    {
        $this->assertInternalType('string', $accessToken->getToken());
        $this->assertInternalType('string', $accessToken->getRefreshToken());

        $this->assertTrue(strlen($accessToken->getToken()) == 40);
        $this->assertTrue(strlen($accessToken->getRefreshToken()) == 40);
    }

    /**
     * @depends testCanLogin
     */
    public function testLoginLinks(AccessToken $accessToken)
    {
        $links = $accessToken->getParam('_links');

        $this->assertNotEmpty($links);

        $this->assertNotEmpty($links['user']);

        $this->client->setAccessToken($accessToken);

        $user = $this->client->get($links['user']);

        /** @var Response $response */
        $response = $user->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $data = $user->parse();

        $this->assertEquals(strtolower($this->email), strtolower($data['email']));
    }

    /**
     * @depends testCanLogin
     * @todo
     */
    public function testFollowLoginLinks(AccessToken $accessToken)
    {
        $links = $accessToken->getParam('_links');

        $uriUser = $links['user'];
        $uriOrganization = $links['organization'] . '?fields=uid,name&links=';

        $this->client->setAccessToken($accessToken);

        $response = $this->client->get([
            $uriUser,
            $uriOrganization,
        ]);

        $uris = $response->parse();

        $user = $uris[$uriUser];
        $organizations = $uris[$uriOrganization];

        foreach ($organizations['data'] as $organization) {
//			var_dump($organization['name']);
//			var_dump($organization['_embed']['subscription']['uid']);
        }
    }

    public function testFailLogin()
    {
        $client = new Client('stqryapp');

        $this->setExpectedException('GuzzleHttp\Exception\ClientException');

        $client->getLoginToken('fake', 'fakepass');
    }

    public function testRequireAccessTokenBeforeGet()
    {
        $this->setExpectedException('Exception');

        $this->client->get('/');
    }
}
