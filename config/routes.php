<?php

/** @var \Slim\App $app */

$app->get('/', \Mlab\BudetControl\Http\Controller\HomeController::class . ':index');

$app->post('/savemail', \Mlab\BudetControl\Http\Controller\HomeController::class . ':saveContact');
