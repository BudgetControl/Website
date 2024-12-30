<?php

/** @var \Slim\App $app */

$app->get('/', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':index');
$app->get('/about-budgetcontrol', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':aboutUs');
$app->get('/documentation', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':documentation');
$app->get('/budgetcontrol-for-professional-use', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':budgetControlForProfessionalUse');
$app->get('/login', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':login');
$app->get('/donations', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':donations');
$app->get('/thanks', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':thanks');
$app->get('/contact', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':contact');

$app->post('/be/api/subscribe', \Mlab\BudetControl\Http\Controller\SubscribeController::class . ':subscribe');
$app->post('/be/api/contact', \Mlab\BudetControl\Http\Controller\SubscribeController::class . ':contact');

$app->get('/terms', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':terms');
$app->get('/privacy', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':privacy');
$app->get('/team/contributors', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':contributors');

/** WORDPRESS BLOG URLs */
$app->group('', function () use ($app) {
    $app->get('/blog', \Mlab\BudetControl\Http\Controller\BlogController::class . ':index');
    $app->get('/blog/articles/{category}/{slug}', \Mlab\BudetControl\Http\Controller\WordpressController::class . ':show');
})->add(new \Mlab\BudetControl\Http\Middleware\CachingMiddleware());

$app->get('/api/wordpress/posts/clear-cache', \Mlab\BudetControl\Http\Controller\WordpressController::class . ':postCache')->add(new \Mlab\BudetControl\Http\Middleware\AuthApiMiddleware());