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
$email = 'daniel.winter@stqry.com';
$password = 'blah';
