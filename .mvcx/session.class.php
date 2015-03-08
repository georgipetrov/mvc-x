<?php

class Session {

	public $data;
	
	function __construct() {
		if (!isset($_SESSION)) {
			session_start();	
		}
		$this->data = $_SESSION;
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
		$this->data = $_SESSION;
	}

	public function get($key) {
		if (isset($_SESSION[$key])) 
			return $_SESSION[$key];	
		else 
			return '';
	}
	
	public function remove($key) {
		unset($_SESSION[$key]);
		$this->data = $_SESSION;
	}
	
	public function flashNotification($msg,$status='success',$redirect='',$persistVars=false) {
		$this->set('flash',array('msg'=>$msg,'status'=>$status));
		if ($persistVars!=false) {
			$this->set('flash-vars',$persistVars);
		}
		if (!empty($redirect)) {
			header('Location: '.$redirect, true, 301);
			exit;
		}
	}
}
