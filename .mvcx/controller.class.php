<?php
abstract class Controller extends Base {
	public $autoRender = true;
	public $autoPersist = false;
	public $controller;
	public $action;

	function __construct($registry) {
        parent::__construct($registry);
		$this->controller = $this->app->router->controller;
		$this->action = $this->app->router->action;
	}
	
	function set($key, $value='') {
        if (!is_array($this->view_vars)) $this->view_vars = array();

		if (is_array($key)) {
			$this->view_vars = array_merge($this->view_vars, $key);
		} else {
			$this->view_vars[$key] = $value;	
		}
	}

    private function addAsset(Asset $asset) {
        if (!$this->app_assets) $this->app_assets = new SplObjectStorage();

        $this->app_assets->attach($asset);
    }

    private function getAssets($type, $position = '', $attributes = array()) {
        $results = new SplObjectStorage();
        if ($this->app_assets) {
            foreach ($this->app_assets as $asset) {
                if ($asset->getType() == $type) {
                    if (!empty($position) && $asset->getPosition() != $position) continue;

                    $match = true;
                    foreach ($attributes as $k=>$v) {
                        if ($asset->getAttrib($k) != $v) {
                            $match = false;
                            break;
                        }
                    }
                    if ($match) {
                        $results->attach($asset);
                    }
                }
            }
        }
        return $results;
    }
	
	public function addScript($script, $position = '') {
        $asset = new Asset($this->registry, $script);
        $asset->setType('script');
        $asset->setAttrib('type', 'text/javascript');
        $asset->setPosition($position);
        $this->addAsset($asset);
	}

    public function getScripts($position = '') {
        return $this->getAssets('script', $position);
    }

    public function addLink($href, $attribs = array(), $position = '') {
        $asset = new Asset($this->registry, $href);
        $asset->setType('link');
        $asset->setPosition($position);
        foreach ($attribs as $k=>$v) {
            $asset->setAttrib($k, $v);
        }
        $this->addAsset($asset);
    }

    public function getLinks($position = '') {
        return $this->getAssets('link', $position);
    }

	public function addStyle($href, $position = '', $attribs = array()) {
        $attribs['rel'] = 'stylesheet';
        if (!isset($attribs['type'])) {
            $attribs['type'] = 'text/css';
        }

        $this->addLink($href, $attribs, $position);
	}

    public function getStyles($position = '') {
        return $this->getAssets('link', $position, array('rel' => 'stylesheet'));
    }
	
	public function parentEdit($id) {
		if (!empty($_POST) && !(empty($id))) {
			$data = array_merge(array('id'=>$id),$_POST);
			$model = $this->controller;
			return $this->$model->save($data);	
		} else {
			return false;	
		}
	}
	
	public function parentAdd() {
		if (!empty($_POST)) {
			$data = $_POST;
			$model = $this->controller;
			return $this->$model->save($data);	
		} else {
			return false;	
		}
	}
	
	public function validate($data=array(),$criteria=array()) {
		if (empty($data) && ($this->request->isPost() || $this->request->isPut())) {
			$data = $this->request->post + $this->request->put;
		}
		if (empty($data)) {
			return true;	
		}
		$notvalidated = array();
		// If there is only data and no criteria, it validate all fields if not empty
		if (empty($criteria)) {
			foreach ($data as $kfield => $vfield) {
				if (empty($vfield)) {
					if (!isset($notvalidated['ifempty'])) $notvalidated['ifempty'] = array();
					$notvalidated['ifempty'][] = $kfield;
				}
			}
		} else {
			foreach ($criteria as $k=>$c) {
				if (array_key_exists($c,$data)) {
					if (empty($data[$c])) {
						if (!isset($notvalidated['ifempty'])) $notvalidated['ifempty'] = array();
						$notvalidated['ifempty'][] = $c;	
					}
				} else {
					if (!isset($notvalidated['ifempty'])) $notvalidated['ifempty'] = array();
					$notvalidated['ifempty'][] = $c;	
				}
			}
		}
		if (!empty($notvalidated)) {
			return $notvalidated;	
		} else {
			return true;
		}
	}
	
	public function autoPersist($validate=true,$flash='',$action='') {
		if (empty($flash)) {
			$flash = array(
				'ifempty' => 'Please fill in all required fields',
				'success' => 'Sucessfully saved!',
				'redirect' => ''
			);
		}
		$this->autoPersist = array('flash'=>$flash,'validate'=>$validate,'action'=>$action);
	}
	
	public function redirect($url) {
		$this->app->router->redirect($url);	
	}
	
	public function hafur() { // Handle AJAX File Upload Request
		if (!$this->request->isPost()) {
			return;
			//$this->app->router->redirect();
		}
		$this->autoRender=false;
		$storeFolder = 'uploads';   //2
		$copyToAnotherApp = returnine($this->request->data['copyToAnotherApp']);
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
			
			function fixQuirks($filename) {
				return str_replace(array(' ','.JPG','.PNG','.GIF'),array('-','.jpg','.png','.gif'),$filename);
			}
			
			$targetFile =  $path . DS . $time . $_FILES['file']['name'];  //5
			$targetFile = fixQuirks($targetFile);
			if (!move_uploaded_file($tempFile,$targetFile)) {
				header('HTTP/1.0 404 Not Found');
				echo 'File not uploaded.';
				exit;
			} else {
				if (!empty($copyToAnotherApp)) {
					$thisApp = DS.$this->app->dir.DS;
					$anotherApp = DS.$copyToAnotherApp.DS;
					$newTargetFile = str_replace($thisApp,$anotherApp,$targetFile);
					$newPath = pathinfo($newTargetFile,PATHINFO_DIRNAME);
					if (!file_exists($newPath)) {
						mkdir($newPath,0755,true);
					}
					copy($targetFile,$newTargetFile);
				}
				
				
				header('content-type:text/json');
				if (!empty($uploadDir)) {
					$uploadDir = $this->request->data['uploadDir'].DS;
				}
				$filename = fixQuirks('asset'.DS.'uploads'.DS.$uploadDir.$time . $_FILES['file']['name']);
				echo $filename;
				exit;	
			}
		}
	}
}
