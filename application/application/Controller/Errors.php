<?php

namespace Controller;
class Errors extends \Object\Controller {

	public $title = 'Error Handler';

	/**
	 * Error action
	 */
	public function actionError() {
		$result = '';
		// show human readable messages first
		if (count(\Object\Error\Base::$errors) > 0) {
			$messages = [];
			foreach (\Object\Error\Base::$errors as $k => $v) {
				if ($v['errno'] == -1) {
					foreach ($v['error'] as $k2 => $v2) {
						$messages[] = i18n(null, $v2);
					}
				}
			}
			if (empty($messages)) {
				$messages[] = \I18n(null, 'Internal Server Error') . ': ' . \I18n(null, 500);
			}
			$result.= \Html::message(['type' => 'danger', 'options' => $messages]);
		}
		// show full description second
		if (\Application::get('flag.error.show_full') && count(\Object\Error\Base::$errors) > 0) {
			foreach (\Object\Error\Base::$errors as $k => $v) {
				$result.= '<h3>' . \Object\Error\Base::$error_codes[$v['errno']] . ' (' . $v['errno'] . ') - ' . implode('<br/>', $v['error']) . '</h3>';
				$result.= '<br />';
				$result.= '<div>File: ' . $v['file'] . ', Line: ' . $v['line'] . '</div>';
				$result.= '<br />';
				// showing code only when we debug
				if (\Debug::$debug) {
					$result.= '<div><pre>' . $v['code'] . '</pre></div>';
					$result.= '<br />';
					$result.= '<div><pre>' . implode("\n", $v['backtrace']) . '</pre></div>';
				}
				$result.= '<hr />';
			}
		}
		echo $result;
		// clear our onload
		\Layout::$onload = '';
	}
}