<?php

class Load extends Base {
    function __construct($registry) {
        parent::__construct($registry);
    }

    public function model($name) {
        $path = '';
        foreach ($this->app->router->extensions as $ext_dir) {
            $model_file = $ext_dir . DS . 'model' . DS . $name . '.php';
            $config_path = $ext_dir . DS . 'xconfig.php';
            if (file_exists($model_file)) {
                $path = $model_file;
                include $config_path;
                break;
            }
        }

        if (empty($path)) {
            $path = $this->app->router->path.DS.'model'.DS.$name.'.php';
        }

        if (!file_exists($path)) {
            throw new ModelNotFoundException('Model ' . $name . ' not found in ' . dirname($path));
        }

        require_once $path;
        $themodel = new $name($this->registry);

        if (!empty($xconfig)) {
            $themodel->config = $xconfig;
        }

        $installed_file = dirname($path) . DS . ".extension.installed";
        if (!file_exists($installed_file)) {
            if (is_callable(array($themodel, 'install'))) {
                $themodel->install();
                touch($installed_file);
            }
        }

        $this->$name = $themodel;
        return $themodel;
    }

    public function view($name, $echo = true, $smart_elements = true) {
        $view_vars = $this->view_vars ? $this->view_vars : array();
        $view = new View($this->registry, $name, $view_vars, $smart_elements);

        $view->render();
        if ($echo == true) {
            echo $view->getContent();
        } else {
            return $view->getContent();
        }
    }

    public function x($x) {
        $path = '';
        foreach ($this->app->router->extensions as $ext_dir) {
            $extension_file = $ext_dir . DS . $x . '.php';
            if (file_exists($extension_file)) {
                $path = $extension_file;
                break;
            }
        }

        if (empty($path)) {
            $path = SITE_PATH.DS.'app'.DS.$this->app->dir.DS.DIRNAME_X.DS.trim($x, DS).'.php';
        }

        if (!file_exists($path)) {
            throw new ExtensionNotFoundException('Extension ' . basename($path) . ' not found in ' . dirname($path));
        }

        require_once $path;
    }
}

class ModelNotFoundException extends Exception {}
class ExtensionNotFoundException extends Exception {}
