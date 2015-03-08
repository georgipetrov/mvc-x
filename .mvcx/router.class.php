<?php

class Router extends Base {
	public $path;
	public $args = array();
	public $x = false;
	public $file;
	public $controller;
	public $controllerObject;
	public $modelObject;
	public $action;
	public $extensions;
	
	function __construct($registry) {
        parent::__construct($registry);
	}


	function setPath($path) {
		/*** check if path is a directory ***/
		if (is_dir($path) == false) {
			throw new Exception ('Invalid controller path: `' . $path . '`');
		}
		/*** set the path ***/
		$this->path = $path;
	}
	
	public function redirect($url) {
		header('Location: '.$url);
	}
	
	public function getCurrentUrl() {
		$protocol = '//';
		$host = returnine($_SERVER['HTTP_HOST']);
		$uri = returnine($_SERVER['REQUEST_URI']);
		if (!empty($_SERVER['SERVER_PROTOCOL'])) {
			$protocol = (stripos($_SERVER['SERVER_PROTOCOL'],'https') === false) ? 'http://' : 'https://';
		}
		return "$protocol$host$uri";
	}
	
	public function setAutoPersist($controller) {

		$persistAction = returnine($controller->autoPersist['action'], $this->action);
		
		if ($persistAction == 'index') {
			$data = $this->modelObject->getAll();
			$this->controllerObject->set('persistence',$data);
		}
		if (!empty($this->args[0]) && $persistAction == 'view') {
			$data = $this->modelObject->getAllById($this->args[0]);
			$this->controllerObject->set($data);
			$this->controllerObject->set('persistence',$data);
		}
		if (!empty($this->args[0]) && $persistAction == 'edit') {
			if ($controller->autoPersist['validate'] !== true) {
				if(!empty($controller->autoPersist['validate']['ifempty'])) {
					$msg = $controller->autoPersist['flash']['ifempty'];
				}
				$persistVars = returnine($_POST,false);
				$this->session->flashNotification($msg,'danger',$this->getCurrentUrl(),$persistVars);
				return;
			}
			if ($this->controllerObject->parentEdit($this->args[0])) {
				$persistVars = $this->modelObject->getAllById($this->args[0]);
				$this->session->flashNotification($controller->autoPersist['flash']['success'],'success',$this->getCurrentUrl(),$persistVars);
				return;
			}
		}
		if ($persistAction == 'add') {			
			if ($controller->autoPersist['validate'] !== true) {
				if(!empty($controller->autoPersist['validate']['ifempty'])) {
					$msg = $controller->autoPersist['flash']['ifempty'];
				}
				$persistVars = returnine($_POST,false);
				$this->session->flashNotification($msg,'danger',$this->getCurrentUrl(),$persistVars);
				return;
			}
			if ($this->controllerObject->parentAdd()) {
				$persistVars = returnine($_POST,false);
				$this->session->flashNotification($controller->autoPersist['flash']['success'],'success',$this->getCurrentUrl(),$persistVars);
				return;
			}

		}

	}

	public function loader() {
        try {
            $this->getController();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }

		if (db::table_exists($this->controller)) {
            try {
                $this->load->model($this->controller);
            } catch (ModelNotFoundException $e) {
                //TODO: Do something meaningful. Add debug/log entry maybe?
            }
		}
		
		include $this->file;
		$class = ucfirst($this->controller).'Controller';
		$controller = new $class($this->registry);
		$this->controllerObject = $controller;

		if (is_callable(array($controller, $this->action)) == false) {
			$action = 'index';
		} else {
			$action = $this->action;
		}
		
		if (isset($this->request->data['hafur'])) {
			$controller->hafur();
			return;	
		}
		
		if (count($this->args) == 0) {
			$controller->$action();
		}
		if (count($this->args) == 1) {
			$controller->$action($this->args[0]);
		}
		if (count($this->args) == 2) {
			$controller->$action($this->args[0],$this->args[1]);
		}
		if (count($this->args) > 2) {
			call_user_func_array(array($controller,$action),$this->args);
		}
		
		if ($controller->autoPersist !== false) {
			$this->setAutoPersist($controller);
		}
		if ($controller->autoRender == true) {
			$this->app->load->view($this->controller.DS.$this->action);
		}
	}
	
	
	
	private function getExtensions($dir) {
		$extension_paths = array();
		if (file_exists($dir) && is_dir($dir)) {
			foreach(glob($dir.'/*', GLOB_ONLYDIR) as $dir) {
				if (file_exists($dir.'/xconfig.php')) {
					$extension_paths[] = $dir;
				} else {
					foreach(glob($dir.'/*', GLOB_ONLYDIR) as $dir) {
						if (file_exists($dir.'/xconfig.php')) {
							$extension_paths[] = $dir;
						}
					}
				}
			}
		}
		return $extension_paths;
	}

	private function getController() {
		$route = empty($_GET['rt']) ? '' : $_GET['rt'];
		$route = trim(str_replace($this->app->uri,'',$route),'/');
		if (empty($route)) {
			$route = 'page/index';
		}

		$parts = explode('/', $route);
		if ($parts[0] == 'x') {
			if (isset($parts[1])) {
				$this->x = $parts[1];
				if (isset($parts[2])) {
					array_shift($parts);
				}
				array_shift($parts);
			}
		}
		$this->controller = $parts[0];
		
		if(isset($parts[1])) {
			$this->action = $parts[1];
		}
		
		if(isset($parts[2])) {
			for ($i = 2; $i < count($parts); $i++ ) {
				$this->args[($i-2)] = $parts[$i];	
			}
		}

	
		if (empty($this->controller)) {
			$this->controller = 'page';
		}
	

		if (empty($this->action)) {
			$this->action = 'index';
		}

		
		// graceful loading degradation X > APP > MVC core
		
		$tryorder = array(
			'extensions' => SITE_PATH.DS.'app'.DS.$this->app->dir.DS.DIRNAME_X,
			'app' => SITE_PATH.DS.'app'.DS.$this->app->dir,
			'mvc' => SITE_PATH.DS.'.mvcx'.DS.'mvc'
		);

		$tryorder['extensions'] = $this->getExtensions($tryorder['extensions']);
		$this->extensions = $tryorder['extensions'];

		$filepath = '';
		foreach ($tryorder as $try) {
			if (is_array($try)) {
				if ($this->x !== false) {
					$trypath = SITE_PATH.DS.'app'.DS.$this->app->dir.DS.DIRNAME_X.DS.$this->x.DS.'controller'.DS.$this->controller.'.php';
					
					if (is_file($trypath) && is_readable($trypath)) {
						if (stripos(file_get_contents($trypath),'extends xcontroller') !== false) {
							$filepath = $trypath;
							$this->setPath(SITE_PATH.DS.'app'.DS.$this->app->dir.DS.DIRNAME_X.DS.$this->x);
							break;
						}
					} 	
				} else {
					foreach ($try as $x) {
						$trypath = $x . DS . 'controller'. DS . $this->controller . '.php';
						if (is_file($trypath) && is_readable($trypath) == true) {
							if (stripos(file_get_contents($trypath),'extends controller') !== false) {
								$filepath = $trypath;
								$this->setPath($x);
								break 2;
							}
	
						} 
					}
				}
			} else {
				$trypath = $try . DS . 'controller'. DS . $this->controller . '.php';
				
				if (is_file($trypath) && is_readable($trypath) == true) {
					$filepath = $trypath;
					$this->setPath($try);
					break;
				} 
			}
		}
		
		if (!empty($filepath)) {
			$this->file = $filepath;
		} else {
			// Show error page
			foreach ($tryorder as $type => $try) {
				if ($type == 'extensions') {
					continue;	
				}
				$trypath = $try . DS . 'controller'. DS . 'error.php';
				
				if (is_file($trypath) && is_readable($trypath) == true) {
					$filepath = $trypath;
					break;
				} 

			}
			if (!empty($filepath)) {
				$this->file = $filepath;
				$this->controller = 'error';
				$this->action = 'notfound';
			} else {
				throw new ControllerNotFoundException ('Error with unspecified page.');
			}
		}
	}

}

class ControllerNotFoundException extends Exception {}
