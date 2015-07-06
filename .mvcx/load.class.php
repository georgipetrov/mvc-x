<?php

class Load extends Base {
    function __construct($registry) {
        parent::__construct($registry);
    }

    public function model($name) {
        $path = $this->app->router->path.DS.'model'.DS.$name.'.php';
        if (!file_exists($path)) {
            throw new ModelNotFoundException('Model ' . $name . ' not found in ' . dirname($path));
        }
        require_once $path;
        $themodel = new $name($this->registry);
        $this->$name = $themodel;
        $this->router->modelObject = $themodel;
    }

    public function view($name, $echo = true, $smart_elements = true) {
        $view_vars = $this->view_vars ? $this->view_vars : array();
        $view = new View($this->registry, $name, $view_vars, $smart_elements);

        $content = $view->render();
        if ($echo == true) {
            echo $content;
        } else {
            return $content;
        }

    }

    public function x($x) {
        $path = SITE_PATH.DS.'app'.DS.$this->app->dir.DS.DIRNAME_X.DS.trim($x, DS).'.php';
        if (!file_exists($path)) {
            throw new ExtensionNotFoundException('Extension ' . basename($path) . ' not found in ' . dirname($path));
        }
        require_once $path;
    }
}

class ModelNotFoundException extends Exception {}
class ExtensionNotFoundException extends Exception {}
