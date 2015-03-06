<?php

class Load {
    private $app;
    private $registry;
    public $session;
    public $request;
    private $vars = array();

    function __construct($app,$session,$request, $registry) {
        $this->app = $app;
        $this->session = $session;
        $this->request = $request;
        $this->registry = $registry;
    }

    public function __set($index, $value) {
        $this->vars[$index] = $value;
    }

    public function setvars($vars) {
        $this->vars = $vars;
    }

    public function model($name) {
        $path = $this->app->router->path.DS.'model'.DS.$name.'.php';
        if (!file_exists($path)) {
            throw new ModelNotFoundException('Model ' . $name . ' not found in ' . dirname($path));
        }
        include $path;
        $themodel = new $name($this->registry);
        $this->app->router->controllerObject->$name = $themodel;
        $this->app->router->modelObject = $themodel;
    }

    public function view($name, $echo = true, $smart_elements = true) {
        $view = new View($this->registry, $name, $this->vars, $smart_elements);

        $content = $view->render();
        if ($echo == true) {
            echo $content;
        } else {
            return $content;
        }

    }

}

class ModelNotFoundException extends Exception {}
