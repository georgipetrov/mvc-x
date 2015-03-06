<?php
class Registry {
    private $vars;

    public function __construct() {
        $this->vars = array();
    }

    public function __get($name) {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        }
        return NULL;
    }

    public function __set($name, $value) {
        $this->registry->vars[$name] = $value;
    }

    public function get($name) {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        }
        return NULL;
    }

    public function set($name, $value) {
        $this->vars[$name] = $value;
    }
}
