<?php
class View extends Base {
    private $name;
    private $vars;
    private $smart_elements;

    public function __construct($registry, $name, $vars = array(), $smart_elements=true) {
        parent::__construct($registry);

        $this->name = $name;
        $this->vars = $vars;
        $this->smart_elements = $smart_elements;
    }

    public function set ($key, $value) {
        $this->vars[$key] = $value;
    }

    private function getDebugInfo($path) {
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
            $debug_info .= '<table class="table" style="color:#222"><thead><tr><th>Custom logs</th></tr></thead>';        
            foreach ($this->log->getDebugLogs() as $key=>$value) {
                if (is_array($value) || is_object($value)) {
                    $debug_info .= '<tr><td>' . $key . '</td><td><pre>' . print_r($value, true) . '</pre></td></tr>';
                } else {
                    $debug_info .= '<tr><td>' . $key . '</td><td>' . print_r($value, true) . '</td></tr>';
                }
            }
            $debug_info .= '</table>';        
            $debug_info .= '</code>';
        }
        return $debug_info;
    }

    public function render() {
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
                    $trytpl = $ext_dir.DS.'view' . DS . $this->name . '.tpl';
                    if (file_exists($trytpl)) {
                        $path = $trytpl;
                        break 2;
                    }
                }
            }
        }

        if (empty($path) || !file_exists($path)) {
            $path = $this->app->router->path . DS . 'view' . $template . DS . $this->name . '.tpl';
        }

        if (!file_exists($path)) {
            $path = SITE_PATH . DS . 'app'. DS .$this->app->dir. DS .'view'. $template . DS . $this->name . '.tpl';
            if (!file_exists($path)) {
                $path = SITE_PATH . DS .'.mvcx'. DS .'mvc'. DS .'view'. $template . DS . $this->name . '.tpl';
            }
        }

        // If the view is not found in the specified template, try without template
        if (!file_exists($path) && !empty($template)) {
            $path = $this->app->router->path . DS . 'view' . DS . $this->name . '.tpl';
            if (!file_exists($path)) {
                $path = SITE_PATH . DS . 'app'. DS .$this->app->dir. DS .'view' . DS . $this->name . '.tpl';
                if (!file_exists($path)) {
                    $path = SITE_PATH . DS .'.mvcx'. DS .'mvc'. DS .'view'. DS . $this->name . '.tpl';
                }
            }
        }

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

        extract($this->vars);

        if (!empty($this->session->data['flash-vars'])) {
            extract($this->session->data['flash-vars']);
            unset($_SESSION['flash-vars']);
        }
        ob_start();
        include $path;
        $content = ob_get_contents();
        ob_end_clean();

        if ($this->app->smart_elements == true && $this->smart_elements == true) {
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

                    $view = new View($this->registry, $view_name, $view_vars, $this->smart_elements);
                    $viewcontent = $view->render();
                    $content = str_replace($match,$viewcontent,$content);

                }
            }
        } 

        return $content;
    }
}

class ViewNotFoundException extends Exception {}
