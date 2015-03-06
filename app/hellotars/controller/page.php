<?php

class PageController extends Controller {
    public function index() {
        $myVar = 'test';
        $myArr = array('key1' => 1, 'key2' => 2);
        $this->log->debug('myVar', $myVar);
        $this->log->debug(compact('myArr'));
    }

    public function notifications() {

        if ($this->request->isPost()) {

            $this->session->flashNotification($this->request->data('msg'),$this->request->data('status'),'/mvc-x/page/notifications');	

        }
    }
}
