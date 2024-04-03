<?php

/** @var \Slim\App $app */

$app->get('/', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':index');
$app->get('/about-budgetcontrol', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':aboutUs');
$app->get('/documentation', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':documentation');
$app->get('/budgetcontrol-for-professional-use', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':budgetControlForProfessionalUse');
$app->get('/login', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':login');
$app->get('/donations', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':donations');
$app->get('/thanks', \Mlab\BudetControl\Http\Controller\RoutingController::class . ':thanks');
