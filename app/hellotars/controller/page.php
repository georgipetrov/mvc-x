<?php

class PageController extends Controller {
    public function index() {
        $myVar = 'MVC-X FTW';
        $myArr = array('key1' => 1, 'key2' => 2);
        $this->log->debug('myVar', $myVar);
        $this->log->debug(compact('myArr'));
        $this->log->debug('time', date('d M Y H:i:s'));
    }

    public function notifications() {

        if ($this->request->isPost()) {

            $this->session->flashNotification($this->request->data('msg'),$this->request->data('status'),'/page/notifications');	

        }
    }
}
