<?php

namespace App;

class Router
{
    public static function get($uri, $controller)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($uri === $_SERVER['REQUEST_URI']) {
                $controller->__invoke();
            }
        }
    }
    
    public static function post($uri, $controller)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($uri === $_SERVER['REQUEST_URI']) {
                $controller->__invoke();
            }
        }
    }
}