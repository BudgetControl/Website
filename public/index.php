<?php
require_once __DIR__.'/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = \Slim\Factory\AppFactory::create();

require __DIR__ . "/../config/bootstrap.php";


/**
 * The routing middleware should be added earlier than the ErrorMiddleware
 * Otherwise exceptions thrown from it will not be handled by the middleware
 */
require_once __DIR__ . "/../config/middleware.php";

require __DIR__ . "/../config/routes.php";

// Run app
$app->run();