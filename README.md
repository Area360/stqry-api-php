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

As this is a private repo, you will be asked to enter Github username and password on the first update.

### Usage

    $client = new Client('stqryapp');
    
#### Login / access token

    $email = 'daniel.winter@stqry.com';
    $password = '123';
    
    $accessToken = $client->getLoginToken($email, $password);

#### Fetch user information

    $links = $accessToken->getParam('_links');
    
    $response = $client->get($links['user']);

    $user = $response->parse();
    
    var_dump($user['first_name'], $user['last_name']);
