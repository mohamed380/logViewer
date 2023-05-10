<?php

use App\Controllers\HomeController;
use App\Router;

return array(
    ['/', [HomeController::class, 'home'], Router::GET_METHOD],
);