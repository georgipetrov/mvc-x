<?php
abstract class Base {
    protected $registry;
    private $null = NULL;

    public function __construct(Registry $registry) {
        $this->registry = $registry;
    }

    public function &__get($name) {
        if ($this->registry != NULL) {
            return $this->registry->get($name);
        }
        return $this->null;
    }

    public function __set($name, $value) {
        if ($this->registry != NULL) {
            $this->registry->set($name, $value);
        }
    }
}
