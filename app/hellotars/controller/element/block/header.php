<?php
class BlockHeaderController extends Controller {
    public function beforeRender() {
        $this->log->debug('block:header', 'This is coming from the BlockHeaderController');
    }
}
