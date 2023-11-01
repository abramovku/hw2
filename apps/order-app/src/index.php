<?php
require __DIR__ . '/vendor/autoload.php';

use App\Lib\App;
use App\Lib\Router;

Router::get('/', function () {
    $greetings = getenv('GRITINGS');
    if (!empty($greetings)) {
        echo $greetings;
    } else {
        echo 'Hello World';
    }    
});

Router::get('/migration', array(new \App\Controllers\OrderController(), 'migrate'));

Router::get('/user/([0-9]*)', array(new \App\Controllers\OrderController(), 'index'));

Router::delete('/user/([0-9]*)', array(new \App\Controllers\OrderController(), 'delete'));

Router::put('/user/([0-9]*)', array(new \App\Controllers\OrderController(), 'update'));

Router::post('/user', array(new \App\Controllers\OrderController(), 'create'));

Router::get('/metrics', array(new \App\Controllers\LoginController(), 'metrics'));

App::run();