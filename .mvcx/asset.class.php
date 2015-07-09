<?php
class Asset extends Base {
    private $href;
    private $type;
    private $attribs;
    private $position;

    public function __construct($registry, $href) {
        parent::__construct($registry);
        $this->href = $this->resolveHref($href);
        $this->type = '';
        $this->attribs = array();
        $this->position = '';
    }

    public function getHref() { return $this->href; }

    public function setType($type) { $this->type = $type; }
    public function getType() { return $this->type; }

    public function setAttrib($attrib, $value) { $this->attribs[$attrib] = $value; }
    public function getAttrib($attrib) { return isset($this->attribs[$attrib]) ? $this->attribs[$attrib] : NULL; }

    public function setPosition($pos) { $this->position = $pos; }
    public function getPosition() { return $this->position; }

    private function resolveHref($href) {
        $config = $this->app->getConfig();
        $template = '';
        if (!empty($config['template'])) {
            $template = DS . 'template' . DS . $config['template'];
        }

        $asset_path = DS . 'asset' . DS . $href;
        $template_path = DS . 'view' . $template . $asset_path;
        $view_path = DS . 'view' . $asset_path;

        $path = SITE_PATH . DS . 'app' . DS . $this->app->dir . $template_path;

        if (file_exists($path)) {
            return '//' . $this->app->url . str_replace(DS, '/', $asset_path);
        }

        $path = SITE_PATH . DS . 'app' . DS . $this->app->dir . $view_path;

        if (file_exists($path)){
            return '//' . $this->app->url . str_replace(DS, '/', $asset_path);
        }

        return $href;
    }
}
