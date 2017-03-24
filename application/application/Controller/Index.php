<?php

namespace Controller;
class Index extends \Object\Controller {

	public $title = 'Index';

	public function actionIndex() {
		echo \Application::get('environment');
		echo "<h3>This is index controller</h3>";
	}
}