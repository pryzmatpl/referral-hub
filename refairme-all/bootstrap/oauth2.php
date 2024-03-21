<?php
//configuration for oauth2

use Chadicus\Slim\OAuth2\Middleware;
use Chadicus\Slim\OAuth2\Http\RequestBridge;
use Chadicus\Slim\OAuth2\Http\ResponseBridge;
use OAuth2\GrantType;
use OAuth2\Storage;

$storage = new Storage\Memory(
    [
        'shopify_credentials' => [
            'administrator' => [
                'id' => 'e585ed908e1f79483ccc495eb94ed5cd',
                'secret' => '4eea6cf6aa29ec74ea019ac1f3a0e2a6',
            ],
        ],
    ]
);

// create the oauth2 server
$server = new OAuth2\Server(
    $storage,
    [
        'access_lifetime' => 3600,
    ],
    [
        new GrantType\ClientCredentials($storage),
    ]
);