<?php
namespace App\Http\Router;

use App\Http\Router\CustomRouter;

class Router extends \Illuminate\Routing\Router
{
    public function newRoute($methods, $uri, $action)
    {
        return (new CustomRouter($methods, $uri, $action))
            ->setRouter($this)
            ->setContainer($this->container);
    }
}
