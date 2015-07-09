<?php
class View extends Base {
    protected $name;
    protected $vars;
    protected $smart_elements;
    protected $children;
    protected $content;
    protected $rendered;

    public function __construct($registry, $name, $vars = array(), $smart_elements=true) {
        parent::__construct($registry);

        $this->name = $name;
        $this->vars = $vars;
        $this->smart_elements = $smart_elements;
        $this->children = new SplObjectStorage();
        $this->content = '';
        $this->rendered = false;
    }

    public function set ($key, $value) {
        $this->vars[$key] = $value;
    }

    public function getContent() {
        if (!$this->rendered) {
            $this->render();
        }

        foreach ($this->children as $child) {
            $subview_content = $child->getContent();
            $start = strpos($this->content, $child->getMatchStr());
            $length = strlen($child->getMatchStr());
            $this->content = substr_replace($this->content, $subview_content, $start, $length);
        }
        return $this->content;
    }

    public function render() {
        $path = $this->locateFile('view', 'tpl');

        if (file_exists($path) == false) {
            throw new ViewNotFoundException('View file not found for '. $this->name);
        }

        if (!isset($_BASEHREF)) { 
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
            $_BASEHREF = $protocol.$this->app->url.'/';
        }
        $_DEBUG = '';
        if ($this->app->debug_mode) {
            $_DEBUG = $this->getDebugInfo($path);
        }
        $_CONTROLLER = $this->app->router->controller;
        $_ACTION = $this->app->router->action;
        $_APPPATH = $this->app->router->path;
        $_BODYCLASS = $_CONTROLLER . ' ' . $_ACTION;
        $_TEMPLATE = $this->app->template;

        //load element's controller
        if (get_class($this) == "SubView") {
            $view_controller = $this->locateFile('controller', 'php');
            if (empty($view_controller)) {
                $controller_dir = preg_replace('@view/.*?' . $this->name . '\.tpl$@', '', $path);
                $view_controller = $controller_dir . 'controller' . DS . $this->name . '.php';
            }
            if (file_exists($view_controller)) {
                require_once $view_controller;
                $parts = explode(DS, $this->name);
                $className = implode('', array_map('ucfirst', array_map('strtolower', array_slice($parts, -2)))) . 'Controller'; //this is supposed to produce something like BlockHeaderController
                $controller_obj = new $className($this->registry);
                if (is_callable(array($controller_obj, 'beforeRender'))) {
                    $this->view_vars = $this->vars;
                    $controller_obj->beforeRender();
                    $this->smart_elements = $controller_obj->smart_elements;
                    $this->vars = array_merge($this->vars, $this->view_vars);
                }
            }
        }

        extract($this->vars);

        if (!empty($this->session->data['flash-vars'])) {
            extract($this->session->data['flash-vars']);
            unset($_SESSION['flash-vars']);
        }

        ob_start();
        include $path;
        $content = ob_get_contents();
        ob_end_clean();

        if ($this->app->smart_elements && $this->smart_elements) {
            preg_match_all("/\[[^\]]*\]/", $content, $matches); //TODO: Test if this works with several elements on one line. May need to make the regex non-greedy and put a capture group
            if (!empty($matches[0])) {

                foreach ($matches[0] as $match) {
                    $m = explode(':',trim($match,'[]'));
                    if (count($m) < 2) continue;

                    $view_vars = array();
                    if (stripos($m[1],'=') !== false) {
                        $phpcode = trim(substr($m[1],stripos($m[1],' ')));
                        $m[1] = trim(str_replace($phpcode,'',$m[1]));
                        $vars = NULL;
                        preg_match_all('/(\w+?)\=/', $phpcode, $vars);
                        if ($vars) {
                            $values = preg_split('/\s?\w+?\=/', $phpcode);
                            array_shift($values); //the first element is an empty string because our string starts with a var name

                            foreach ($vars[1] as $key=>$val) {
                                if (isset($values[$key])) {
                                    $view_vars[$val] = $values[$key];
                                }
                            }
                        }
                    }
                    $view_name = 'element'.DS.$m[0].DS.$m[1];

                    $view = new SubView($this->registry, $view_name, $view_vars, $this->smart_elements);
                    $view->setParent($this);
                    $view->setMatchStr($match);
                    $this->children->attach($view);
                    if ($m[0] != 'widget' || !in_array($m[1], array('js', 'css'))) {
                        $view->render();
                    }
                }
            }
        } 

        $this->rendered = true;
        $this->content = $content;
    }

    protected function locateFile($type, $ext) {
        $x = '';
        $template = '';
        if ($this->app->template !== false && !empty($this->app->template)) {
            $template = DS . 'template' . DS . $this->app->template;
        }

        $xconfig = array();
        foreach ($this->app->router->extensions as $ext_dir) {
            include $ext_dir.DS.'xconfig.php';
            foreach ($xconfig as $xc) {
                if (!empty($xc['override_views']) && $xc['override_views'] == true) {
                    $tryfile = $ext_dir . DS . $type . DS . $this->name . '.' . $ext;
                    if (file_exists($tryfile)) {
                        $path = $tryfile;
                        break 2;
                    }
                }
            }
        }

        if (empty($path) || !file_exists($path)) {
            $path = $this->app->router->path . DS . $type . $template . DS . $this->name . '.' . $ext;
        }

        if (!file_exists($path)) {
            $path = SITE_PATH . DS . 'app'. DS .$this->app->dir . DS . $type . $template . DS . $this->name . '.' . $ext;
            if (!file_exists($path)) {
                $path = SITE_PATH . DS . '.mvcx' . DS . 'mvc' . DS . $type . $template . DS . $this->name . '.' . $ext;
            }
        }

        // If the view is not found in the specified template, try without template
        if (!file_exists($path) && !empty($template)) {
            $path = $this->app->router->path . DS . $type . DS . $this->name . '.' . $ext;
            if (!file_exists($path)) {
                $path = SITE_PATH . DS . 'app'. DS . $this->app->dir . DS . $type . DS . $this->name . '.' . $ext;
                if (!file_exists($path)) {
                    $path = SITE_PATH . DS . '.mvcx' . DS . 'mvc' . DS . $type . DS . $this->name . '.' . $ext;
                }
            }
        }

        return $path;
    }

    protected function getDebugInfo($path) {
        $debug_info = '';
        if ($this->app->debug_mode == 1) {
            $debug_info .= '<code style="padding:5px 20px; margin:0;border-top:2px dashed #c7254e; display:block;">';
            $debug_info .= '<h3>DEBUG</h3>';


            $debug_info .= '<table class="table" style="color:#222"><thead><tr><th colspan="2">Database Queries</th></tr></thead>';        
            foreach (db::$log as $k=> $lg) {
                $debug_info .= '<tr><td>'.($k+1)."\t".$lg['time'].' microseconds'." \t".$lg['query'].'</td></tr>';
            }  
            $debug_info .= '</table>';        
            $debug_info .= '<table class="table" style="color:#222"><thead><tr><th>Page Lifecycle</th></tr></thead>';        
            $debug_info .= "<tr><td>Loaded view:</td><td>".$path.'</td></tr>';	
            $debug_info .= "<tr><td>Loaded controller:</td><td>".$this->app->router->file.'</td></tr>';            
            $debug_info .= '</table>';        
            foreach ($this->log->getDebugGroups() as $group) {
                $debug_info .= '<table class="table" style="color:#222"><thead><tr><th>' . $group . '</th></tr></thead>';        
                foreach ($this->log->getDebugLogs($group) as $log) {
                    $debug_info .= '<tr><td>' . $log[0] . '</td><td><pre>' . print_r($log[1], true) . '</pre></td></tr>';
                }
                $debug_info .= '</table>';        
            }
            $debug_info .= '</code>';
        }
        return $debug_info;
    }
}

class ViewNotFoundException extends Exception {}
