<?php
class BlockHeaderController extends Controller {
    public function beforeRender() {
        $this->addScript('asset/js/jquery.min.js', 'header');
        $this->addScript('asset/js/bootstrap.min.js', 'header');
        $this->addStyle('asset/css/bootstrap.min.css', 'header');
        $this->addStyle('asset/css/stylesheet.css', 'header');
    }
}
