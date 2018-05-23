<?php

class IndexController extends BaseController {
	// Show index page
	public static function index() {
		View::make('index/index.html');
	}
}
?>