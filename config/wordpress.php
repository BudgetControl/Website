<?php

if (env("WORDPRESS_URL") === null) {
    throw new \Exception("WORDPRESS_URL is not set in .env file");
}

if (env("WORDPRESS_API_PREFIX") === null) {
    throw new \Exception("WORDPRESS_API_PREFIX is not set in .env file");
}

$client = new \GuzzleHttp\Client([
    'base_uri' => env("WORDPRESS_URL"),
    'timeout'  => 2.0,
    'headers' => [
        'User-Agent' => 'BudgetControl - WordPress PHP SDK - 1.0',
        'Accept' => 'application/json',
    ],
    'verify' => false, // Disable SSL certificate verification
]);

$wordpressClient = new \Mlabfactory\WordPress\WordPress(
    env("WORDPRESS_URL"),
    $client,
    env("WORDPRESS_API_PREFIX"),
);
