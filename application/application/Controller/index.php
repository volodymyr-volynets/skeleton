<?php

class controller_index extends object_controller {

	public $title;
	public $acl = [
		'public' => 1,
		'authorized' => 1
	];

	public function action_index() {
		echo application::get('environment');
		echo "<h3>This is index controller</h3>";
	}
}