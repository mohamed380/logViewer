<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = new App\Application;

echo $app->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);