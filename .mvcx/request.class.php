<?php

class Request {

	public $data;
	public $post;
	public $put;

	function __construct() {
		$this->data = returnine($_REQUEST,array());
		$this->post = returnine($_POST,array());
		$this->put = returnine($_PUT,array());
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