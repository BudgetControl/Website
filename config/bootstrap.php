<?php
require_once __DIR__.'/../vendor/autoload.php';

use Monolog\Level;
use Illuminate\Database\Capsule\Manager as Capsule;

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