<?php
require __DIR__ . '/vendor/autoload.php';

use App\Lib\App;
use App\Lib\Router;

Router::get('/)', array(new \App\Controllers\OrderController(), 'index'));
Router::post('/)', array(new \App\Controllers\OrderController(), 'data'));


App::run();