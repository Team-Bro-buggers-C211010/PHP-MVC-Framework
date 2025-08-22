<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../functions.php';

use app\core\Application;

$app = new Application(dirname(__DIR__));

$app->router->get('/', 'home');

$app->router->get('/contact', 'contact');


$app->run();
