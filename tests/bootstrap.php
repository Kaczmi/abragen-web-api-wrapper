<?php

use Tester\Assert;
use AbraApi\AbraApi;

require __DIR__ . '/../vendor/autoload.php'; 

Tester\Environment::setup();

// autoload AbraApi classes
$loader = new Nette\Loaders\RobotLoader;
$loader->addDirectory(__DIR__ . '/../src');
$loader->setTempDirectory(__DIR__ . '/../temp');
$loader->setAutoRefresh(true);
$loader->register();

// create default variable for abraApi access
$abraApi = new AbraApi("host.example:8080", "database", "API", "password");
