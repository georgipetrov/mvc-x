<?php

abstract class Controller extends Base {
	public $autoRender = true;
	public $autoPersist = false;
	public $controller;
	public $action;
	public $vars = array();
	function __construct($registry) {
        parent::__construct($registry);
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
	
	public function hafur() { // Handle AJAX File Upload Request
		if (!$this->request->isPost()) {
			return;
			//$this->app->router->redirect();
		}
		$this->autoRender=false;
		$storeFolder = 'uploads';   //2
		$uploadDir = '';
		if (!empty($this->request->data['uploadDir'])) {
			$uploadDir = DS.$this->request->data['uploadDir'];	
		}
		$path = $this->app->router->path.DS.'view'.DS.'asset'.DS.'uploads'.$uploadDir;
		if (!file_exists($path)) {
			mkdir($path,0755,true);
		}
		
		
		
		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];          //3     
			$time = '';
			if (!empty($this->request->data['useTimestamp'])) {
				$time = time().'.';
			}
			$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
			$allowedFormats  = explode(',',strtolower($this->request->data('allowedFormats')));
			
			if(!in_array($ext,$allowedFormats)) {
				header('HTTP/1.0 404 Not Found');
				echo 'File not uploaded. '.strtoupper($ext)." format is not allowed.";
				exit;
			}
			
			$targetFile =  $path . DS . $time . $_FILES['file']['name'];  //5
			if (!move_uploaded_file($tempFile,$targetFile)) {
				header('HTTP/1.0 404 Not Found');
				echo 'File not uploaded.';
				exit;
			} else {
				header('content-type:text/json');
				if (!empty($uploadDir)) {
					$uploadDir = $this->request->data['uploadDir'].DS;
				}

				$filename = 'asset'.DS.'uploads'.DS.$uploadDir.$time . $_FILES['file']['name'];
				echo $filename;
				exit;	
			}
		}
	}
}
