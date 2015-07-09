<?php
class WidgetCssController extends Controller {
    public function beforeRender() {
        $position = '';
        if(isset($this->view_vars['position'])) {
            $position = $this->view_vars['position'];
        }
        $styles = $this->getStyles($position);
        $this->set('styles', $styles);
    }
}
