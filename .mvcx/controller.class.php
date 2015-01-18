<?php

abstract class Controller {
	protected $app;
	protected $load;
	public $autoRender = true;
	public $autoPersist = false;
	public $controller;
	public $action;
	public $vars = array();
	function __construct($app,$load) {
		$this->app = $app;
		$this->load = $load;
		$this->controller = $this->app->router->controller;
		$this->action = $this->app->router->action;
	}
	
	/**
	 * @all controllers must contain an index method
	 */
	abstract function index();
	
	function set($key,$value='') {
		if ($value == '') {
			$this->load->setvars($key);
		} else {
			$vars = array($key => $value);
			$this->load->setvars($vars);	
		}
	}
	
	public function parentEdit($id) {
		if (!empty($_POST) && !(empty($id))) {
			$data = array_merge(array('id'=>$id),$_POST);
			$model = $this->controller;
			$this->$model->save($data);	
		}
	}
	
	public function parentAdd() {
		if (!empty($_POST)) {
			$data = $_POST;
			$model = $this->controller;
			$this->$model->save($data);	
		}
	}
	
	public function redirect($url) {
		$this->app->router->redirect($url);	
	}
	
	public function __call($method, $args) {
       if(property_exists($this, $method)) {
           if(is_callable($this->$method)) {
               return call_user_func_array($this->$method, $args);
           }
       }
   	}
	
	
}