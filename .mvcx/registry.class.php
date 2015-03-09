<?php
class Registry {
    private $vars;

    public function __construct() {
        $this->vars = array();
    }

    public function &__get($name) {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        } else {
            $this->vars[$name] = NULL;
            return $this->vars[$name];
        }
    }

    public function __set($name, $value) {
        $this->vars[$name] = $value;
    }

    public function &get($name) {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        } else {
            $this->vars[$name] = NULL;
            return $this->vars[$name];
        }
    }

    public function set($name, $value) {
        $this->vars[$name] = $value;
    }
}
