stqry-api-php
=============

### Installation

via composer:

    {
        "repositories": [
            {
                "type": "vcs",
                "url":  "git@github.com:stqry/stqry-api-php.git"
            }
        ],
        "require": {
            "stqry/stqry-api-php": "dev-master"
        }
    }

### Usage
    
#### STQRY grant type

    $client = new Client('myclientid');

    $email = 'daniel.winter@stqry.com';
    $password = '123';
    
    $accessToken = $client->getLoginToken($email, $password);

#### Fetch user information

    $links = $accessToken->getParam('_links');
    
    $response = $client->get($links['user']);

    $user = $response->parse();
    
    var_dump($user['first_name'], $user['last_name']);
    
#### Client credentials grant type

If you have been issued with a client ID and client secret, you would use it like this:

    $client = new Client('myclientid', 'myclientsecert');

    $accessToken = $client->getClientCredentialsToken();

    // Fetch organization story
    $response = $this->client->get('/entity/organization/37070595-1591-42e5-9a1c-f4cccb93d439');

    $organization = $response->parse();
    
    var_dump($organization);
