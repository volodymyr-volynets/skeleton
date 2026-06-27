<?php

namespace Controller;
class Index extends \Object\Controller
{

    public $title = 'Index';

    public function actionIndex()
    {
        \Request::redirect('/Numbers/Users/Users/Controller/Helper/Dashboard');
    }
}
