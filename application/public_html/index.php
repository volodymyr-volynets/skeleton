<?php

// autoloading composer first
if (file_exists('../libraries/vendor/autoload.php')) {
	require('../libraries/vendor/autoload.php');
}

// running application
require('../libraries/vendor/Numbers/Framework/Application.php');
Application::run();