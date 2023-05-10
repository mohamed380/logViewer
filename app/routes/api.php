<?php

use App\Controllers\LogController;
use App\Router;

return array(
    ['/logs', [LogController::class, 'index'], Router::GET_METHOD],
);