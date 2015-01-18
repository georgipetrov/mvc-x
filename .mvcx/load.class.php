<?php

class Load {
	
	
	/*
	* @the registry
	* @access private
	*/
	private $app;
	
	/*
	* @Variables array
	* @access private
	*/
	private $vars = array();
	
	/**
	*
	* @constructor
	*
	* @access public
	*
	* @return void
	*
	*/
	function __construct($app) {
		$this->app = $app;
	}
	
	
	/**
	*
	* @set undefined vars
	*
	* @param string $index
	*
	* @param mixed $value
	*
	* @return void
	*
	*/
	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}
	
	public function setvars($vars) {
		$this->vars = $vars;
	}
	
	private function debug($path) {
		if ($this->app->debug_mode  == 1) {
			echo('<code style="padding:5px 20px; margin:30px 0 0 0;background: #eee;display:block;">');
			echo('<pre>DEBUG MODE</pre>');

		
			foreach (db::$log as $k=> $lg) {
				echo('<pre>'.($k+1)."\t".$lg['time'].' microseconds'." \t".$lg['query'].'</pre>');
			}          
			echo("<pre>Loaded view: \t\t".$path.'</pre>');	
			echo("<pre>Loaded controller: \t".$this->app->router->file.'</pre>');            
			echo('</code>');
		}
	}
	
	function model($name) {
		$path = $this->app->router->path.'/model/'.$name.'.php';
		if (!file_exists($path)) {
			throw new Exception('Model not found in '. $path);
			return false;			
		}
		include $path;
		$themodel = new $name($this->app);
		$this->app->router->controllerObject->$name = $themodel;
		$this->app->router->modelObject = $themodel;
	}
	
	
	function view($name) {
		
		$x = '';
		$path = $this->app->router->path.'/view' . '/' . $name . '.tpl';
		
		if (!file_exists($path)) {
			$path = SITE_PATH . '/app/'.$this->app->dir.'/view' . '/' . $name . '.tpl';
			if (!file_exists($path)) {
				$path = SITE_PATH . DS .'.mvcx'. DS .'mvc'. DS .'view' . DS . $name . '.tpl';
			}
		}	
		
		
		
		if (file_exists($path) == false) {
			throw new Exception('Template not found in '. $path);
			return false;
		}
		
		extract($this->vars);
		
		include ($path); 
		
		$this->debug($path);	


	}
 
}