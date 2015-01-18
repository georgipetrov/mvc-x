<?php

abstract class XController {
	protected $app;
	
	function __construct($app) {
		$this->registry = $app;
	}

	abstract function index();
}