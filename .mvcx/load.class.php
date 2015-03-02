<?php

class Load {
	private $app;
	private $vars = array();
	function __construct($app) {
		$this->app = $app;
	}
	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}
	public function setvars($vars) {
		$this->vars = $vars;
	}
	private function debug($path) {
		$debug_info = '';
		if ($this->app->debug_mode  == 1) {
			$debug_info .= '<code style="padding:5px 20px; margin:30px 0 0 0;background: #eee;display:block;">';
			$debug_info .= '<pre>DEBUG MODE</pre>';

		
			foreach (db::$log as $k=> $lg) {
				$debug_info .= '<pre>'.($k+1)."\t".$lg['time'].' microseconds'." \t".$lg['query'].'</pre>';
			}          
			$debug_info .= "<pre>Loaded view: \t\t".$path.'</pre>';	
			$debug_info .= "<pre>Loaded controller: \t".$this->app->router->file.'</pre>';            
			$debug_info .= '</code>';
		}
		return $debug_info;
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
	
	
	function view($name,$echo=true) {
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
		
		if (!isset($_BASEHREF)) { 
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$_BASEHREF = $protocol.$this->app->url.'/';
		}
		if (!empty($this->app->debug_mode)) {
			$_DEBUG = $this->debug($path);
		}
		extract($this->vars);
		if ($this->app->smart_tags == true) {
			$content = file_get_contents($path); 
			preg_match_all("/\[[^\]]*\]/", $content, $matches);
			if (!empty($matches[0])) {
				foreach ($matches[0] as $match) {
					$m = explode(':',trim($match,'[]'));
					if (count($m) < 2) continue;
					$viewcontent = $this->view('layout/'.$m[0].'/'.$m[1],false);
					$content = str_replace($match,$viewcontent,$content);
				}

				$temp_file = tempnam(sys_get_temp_dir(), 'mvc');
				file_put_contents($temp_file,$content);
				include $temp_file;
				unlink($temp_file);
				return;
			}
		} 
		if ($echo == true) {
			include ($path); 
		} else {
			return file_get_contents($path);	
		}


	}
 
}