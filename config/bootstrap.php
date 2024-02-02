<?php
require_once __DIR__.'/../vendor/autoload.php';

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