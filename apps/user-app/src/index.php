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

Router::get('/migration', array(new \App\Controllers\UserController(), 'migrate'));

Router::get('/user/([0-9]*)', array(new \App\Controllers\UserController(), 'index'));

Router::delete('/user/([0-9]*)', array(new \App\Controllers\UserController(), 'delete'));

Router::put('/user/([0-9]*)', array(new \App\Controllers\UserController(), 'update'));

Router::post('/user', array(new \App\Controllers\UserController(), 'create'));

Router::get('/metrics', array(new \App\Controllers\MainController(), 'metrics'));

App::run();