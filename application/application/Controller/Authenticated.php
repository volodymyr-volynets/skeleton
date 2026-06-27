<?php

namespace Controller;
class Authenticated extends \Object\Controller {

	public $title = 'Authenticated';

	public function __construct()
	{
		parent::__construct();
		//$this->middleware('U/M Authenticated', ['except' => ['OpenAccess']]);
		$this->middleware('U/M Basic Auth', ['except' => ['OpenAccess']]);
	}

	public function actionIndex()
	{
		print_r2('Authenticated');
	}

	public function actionOpenAccess()
	{
		print_r2('Open Access');
	}
}