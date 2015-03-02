<?php

class PageController extends Controller {
	public function index() {
			
	}
	
	public function notifications() {

		if ($this->request->isPost()) {
			
			$this->session->flashNotification($this->request->data('msg'),$this->request->data('status'),'/mvc-x/page/notifications');	
			
		}
	}
}