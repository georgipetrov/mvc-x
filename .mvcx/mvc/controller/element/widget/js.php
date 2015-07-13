<?php
class WidgetJsController extends Controller {
    public function beforeRender() {
        $position = '';
        if(isset($this->view_vars['position'])) {
            $position = $this->view_vars['position'];
        }
        $scripts = $this->getScripts($position);
        $this->set('scripts', $scripts);
    }
}
