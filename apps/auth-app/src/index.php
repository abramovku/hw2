<?php
require __DIR__ . '/vendor/autoload.php';

use App\Lib\App;
use App\Lib\Router;


Router::get('/login', array(new \App\Controllers\LoginController(),'index'));

Router::post('/login', array(new \App\Controllers\LoginController(),'data'));

Router::get('/logout', array(new \App\Controllers\LoginController(),'logout'));

Router::post('/auth', array(new \App\Controllers\LoginController(),'auth'));

App::run();