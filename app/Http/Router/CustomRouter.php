<?php

namespace App\Http\Router;

use Illuminate\Routing\Route;
use Illuminate\Support\Str;

class CustomRouter extends Route
{
    public function __construct($methods, $uri, $action)
    {
        parent::__construct($methods, $uri, $action);
    }

    public function getController()
    {
        if (!$this->controller) {
            $this->customNameSpace();
            $class = $this->parseControllerCallback()[0];
            $this->controller = $this->container->make(ltrim($class, '\\'));
        }

        return $this->controller;
    }

    protected function customNameSpace()
    {
        $action = $this->action;
        $namespace = $action['namespace'];
        if ($namespace === 'App\Controllers') {
            $route = $action['uses'];
            $class_arr = Str::parseCallback($route);
            $class = str_replace('App\Controller', 'AweCustom\Controller', $class_arr[0]);
            if(class_exists($class)){
                $this->action['uses'] = str_replace("App\Controllers", "AweCustom\Controllers", $this->action['uses']);
                $this->action['controller'] = str_replace("App\Controllers", "AweCustom\Controllers", $this->action['controller']);
                $this->action['namespace'] = str_replace("App\Controllers", "AweCustom\Controllers", $this->action['namespace']);
            }
        }
    }
}
