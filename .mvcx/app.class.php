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
     public $configs = array();
     private $config = array();
     private $app;
	 
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
	
	public function initialize() {
        try {
            $app = $this->getAppByUrl();
            $this->config = $app;
        } catch (Exception $e) {
            echo $e->getMessage();
			exit;
		}
		$this->url = $app['url'];
		$this->dir = $app['dir'];
		$this->template = $app['template'];
		$this->smart_elements = $app['smart_elements'];
		$this->dbconfig = $this->getDbConfig();
		$this->debug_mode = $app['debug_mode'];
		$this->uri = $this->getAppUriByUrl($app['url']);
		try {
			$this->dbinstance = db::getInstance($this->dbconfig);
		} catch (Exception $e) {
			$this->dbinstance = NULL;
		}
	}
	
	public function getAppByUrl() {
		$u = rtrim($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '/');
		if (empty($u)) {
			throw new Exception('Invalid URL');
		} 
		
		$matches = array();
		foreach ($this->configs as $appkey => $app) {
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
			$app = $this->configs[$id];
			$app['url'] = $url;
		}

        if (empty($app)) {
            throw new AppNotFoundException('<h1>App not found for '.$_SERVER['HTTP_HOST'].'</h1><h2>Please create and configure at least one app for this site</h2>');
        }
		
        if (!empty($app['timezone'])) {
            date_default_timezone_set($app['timezone']);
        }

		return $app;
	}

    public function setDb($config_key) {
        $config = $this->getDbConfig($config_key);
        $this->dbinstance = db::renewInstance($config);
    }
	
	private function getAppUriByUrl($url) {
		$parts = explode(SITE_HOST,$url);
		$parts[1] = trim($parts[1],'/');
		return (!empty($parts[1])) ? $parts[1] : '';
	}
 
    private function getDbConfig($key = false) {
        if (empty($this->config['db'])) {
            return array();
        }

        if (!$key) {
            if (isset($this->config['db']['default'])) {
                return $this->config['db']['default'];
            } else {
                reset($this->config['db']);
                return current($this->config['db']);
            }
        } else {
            if (isset($this->config['db'][$key])) {
                return $this->config['db'][$key];
            } else {
                throw new ConfigErrorException('Missing required database config ' . $key);
            }
        }
    }
}

class AppNotFoundException extends Exception {}
class ConfigErrorException extends Exception {}
