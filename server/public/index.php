<?php

require  '../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//ini_set( 'session.cookie_httponly', 1 );

use App\Router;
use App\Controllers\Auth;

Router::get('/', function() {
    Auth::getAll();
});

Router::post('/register', function() {
    Auth::create();
});

Router::post('/login', function() {
    Auth::login();
});

Router::post('/logout', function() {
    Auth::logout();
});

Router::post('/update', function() {
    Auth::update();
});