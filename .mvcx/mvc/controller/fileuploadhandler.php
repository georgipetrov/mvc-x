<?php

class FileuploadhandlerController extends Controller {
	public function index() {
		$this->autoRender = false;
		pr($this->app->router->path);
	}

}