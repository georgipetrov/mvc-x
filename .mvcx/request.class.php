<?php

class Request {

	public $data;

	function __construct() {
		
		$data = $_REQUEST;
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


}
