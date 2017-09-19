<?php

namespace Miscellaneous\UnitTests;
class Application extends \PHPUnit\Framework\TestCase {

	/**
	 * test application class initialization
	 */
	public function test_application_class_initialization() {
		// step 1 - class exists
		$this->assertEquals(true, class_exists('application'));
		// step 2 - if application has been run we should have an environment
		$this->assertContains(Application::get('environment'), ['production', 'staging', 'testing', 'development']);
	}
}