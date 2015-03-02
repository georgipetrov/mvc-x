<?php

class Session {

	public $data;

	function __construct() {
		if (!isset($_SESSION)) {
			session_start();	
		}
		$data = $_SESSION;
		$this->data = $data;
	}

	public function data($param='') {
		if (!empty($param)) {
			if (isset($this->data[$param])) {
				return $this->data[$param];
			} else {
				return '';	
			}
		} else {
			return $this->data;	
		}
	}
	
	public function set($key, $value) {
		$_SESSION[$key] = $value;	
	}

	public function get($key) {
		if (isset($_SESSION[$key])) 
			return $_SESSION[$key];	
		else 
			return '';
	}
}
