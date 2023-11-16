<?php
require __DIR__ . '/vendor/autoload.php';

use App\Lib\App;
use App\Lib\Router;

Router::get('/', function () {
    echo 'Hello auth';
});

Router::get('/login', array(new \App\Controllers\LoginController(),'index'));

Router::get('/register', array(new \App\Controllers\RegisterController(),'index'));

Router::post('/login', array(new \App\Controllers\LoginController(),'data'));

Router::post('/register', array(new \App\Controllers\RegisterController(),'index'));

Router::get('/logout', array(new \App\Controllers\LoginController(),'logout'));

Router::get('/auth', array(new \App\Controllers\LoginController(),'auth'));

App::run();