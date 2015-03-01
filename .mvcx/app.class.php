<?php

class App {
	 public $url;
	 public $uri;
	 public $debug_mode;
	 public $dbinstance;
	 public $dir;
	 public $dbconfig;
	 
	/*
	* @the vars array
	* @access private
	*/
	private $vars = array();
	
	
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
	
	/**
	*
	* @get variables
	*
	* @param mixed $index
	*
	* @return mixed
	*
	*/
	public function __get($index) {
		return $this->vars[$index];
	}
	
	function initialize($config) {
		$app = $this->getAppByUrl($config);
		$this->url = $app['url'];
		$this->dir = $app['dir'];
		$this->dbconfig = $app['db'];
		$this->debug_mode = $app['debug_mode'];
		$this->uri = $this->getAppUriByUrl($app['url']);
		try {
			$this->dbinstance = db::getInstance($app['db']);
		} catch (Exception $e) {
			$this->dbinstance = NULL;
		}
	}
	
	private function getAppUriByUrl($url) {
		$parts = explode(SITE_HOST,$url);
		$parts[1] = trim($parts[1],'/');
		return (!empty($parts[1])) ? $parts[1] : '';
	}
	
	function getAppByUrl($config) {
		$u = rtrim($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '/');
		if (empty($u)) {
			throw new Exception('Invalid URL');
			return false;
		} 
		
		$matches = array();
		foreach ($config as $appkey => $app) {
			if (empty($app['url'])) {
				throw new Exception('Configuration error: URL for this app is missing in config.php');
				return false;
			}
			
			if (is_array($app['url'])) {
				foreach ($app['url'] as $url) {
					if (strpos($u,$url) !== false) {
						$matches[$url] = $appkey;
					}
				}
			} else {
				if (strpos($u,$app['url']) !== false) {
					$matches[$app['url']] = $appkey;
				}
			}
		}

		function sortByLength($a,$b){
			return strlen($a)-strlen($b);
		}

		uksort($matches,'sortByLength');

		$appmatch = array_slice($matches,-1,1);
		$app = array();
		foreach ($appmatch as $url=>$id) {
			$app = $config[$id];
			$app['url'] = $url;
		}
		
		return $app;
	}
 
}