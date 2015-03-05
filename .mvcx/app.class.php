<?php

class App {
	 public $url;
	 public $uri;
	 public $template;
	 public $smart_elements;
	 public $debug_mode;
	 public $dbinstance;
	 public $dir;
	 public $dbconfig;
     public $config = array();
	 
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
	
	function initialize() {
        try {
            $app = $this->getAppByUrl();
        } catch (Exception $e) {
            echo $e->getMessage();
			exit;
		}
		$this->url = $app['url'];
		$this->dir = $app['dir'];
		$this->template = $app['template'];
		$this->smart_elements = $app['smart_elements'];
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
	
	function getAppByUrl() {
		$u = rtrim($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '/');
		if (empty($u)) {
			throw new Exception('Invalid URL');
		} 
		
		$matches = array();
		foreach ($this->config as $appkey => $app) {
			if (empty($app['url'])) {
				throw new ConfigErrorException('Configuration error: URL for this app is missing in config.php');
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

		uksort($matches, function ($a,$b) { return strlen($a) - strlen($b); });

		$appmatch = array_slice($matches,-1,1);
		$app = array();
		foreach ($appmatch as $url=>$id) {
			$app = $this->config[$id];
			$app['url'] = $url;
		}

        if (empty($app)) {
            throw new AppNotFoundException('<h1>App not found for '.$_SERVER['HTTP_HOST'].'</h1><h2>Please create and configure at least one app for this site</h2>');
        }
		
		return $app;
	}
 
}

class AppNotFoundException extends Exception {}
class ConfigErrorException extends Exception {}
