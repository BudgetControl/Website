<?php

use Monolog\Level;
use Illuminate\Database\Capsule\Manager as Capsule;
use MadeITBelgium\WordPress\WordPress;
use MadeITBelgium\WordPress\WordPressFacade;
use Mlab\BudetControl\Services\EmailService;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

// Crea un'istanza del gestore del database (Capsule)
$capsule = new Capsule;

// Aggiungi la configurazione del database al Capsule
$capsule->addConnection(require 'database.php');

// Esegui il boot del Capsule
$capsule->bootEloquent();
$capsule->setAsGlobal();

$streamHandler = new \Monolog\Handler\StreamHandler(__DIR__.'/../storage/logs/log-'.date("Ymd").'.log', Level::Debug);
$logger = new \Monolog\Logger('app');
$formatter = new \Monolog\Formatter\SyslogFormatter();
$streamHandler->setFormatter($formatter);
$logger->pushHandler($streamHandler);

/** WORDPRESS client service */
require_once __DIR__ . '/wordpress.php';

/** CACHE SYSTEM */
require_once __DIR__ . '/cache.php';

$routeSiteService = new \Mlab\BudetControl\Services\RouteSiteService($app);

// Set up the Facade application
Illuminate\Support\Facades\Facade::setFacadeApplication([
    'log' => $logger,
    'wordpress-client' => $wordpressClient,
    'cache' => $cache,
    'route-site' => $routeSiteService,
    'mail' => new EmailService()
]);