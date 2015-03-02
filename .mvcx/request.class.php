<?php

class Request {

	public $data;

	function __construct() {
		
		$data = $_REQUEST;
		$this->data = $data;
	}
	
	public function isPost() {
		return (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST');	
	}

	public function isPut() {
		return (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT');	
	}

	public function isGet() {
		return (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET');	
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


}
