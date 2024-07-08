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
			$all_messages = [];
			// throw error in json format if ajax is set
			if (\Application::get('flag.global.__ajax') || \Application::get('flag.global.__accept') == 'application/json') {
				foreach (\Object\Error\Base::$errors as $k => $v) {
					foreach ($v['error'] as $v2) {
						$messages[] = i18n(null, $v2);
					}
				}
				\Layout::renderAs([
					'success' => false,
					'error' => strip_tags2($messages)
				], 'application/json');
			}
			// render html
			foreach (\Object\Error\Base::$errors as $v) {
				if ($v['errno'] == -1) {
					foreach ($v['error'] as $v2) {
						$messages[] = i18n(null, $v2);
					}
				}
				foreach ($v['error'] as $v2) {
					$all_messages[] = $v2;
				}
			}
			if (empty($messages)) {
				$messages[] = i18n(null, 'Internal Server Error') . ': ' . i18n(null, 500);
			}
			if (empty($all_messages)) {
				$all_messages[] = 'Internal Server Error';
			}
			$result.= \HTML::message(['type' => 'danger', 'options' => $messages]);
			// add data to firewall
			$firewalls = \Object\ACL\Resources::getStatic('firewalls', 'primary');
			if (!empty($firewalls)) {
				call_user_func_array($firewalls['method'], [\Request::ip(), $all_messages]);
			}
		}
		// show full description second
		if (\Application::get('flag.error.show_full') && count(\Object\Error\Base::$errors) > 0) {
			foreach (\Object\Error\Base::$errors as $k => $v) {
				$result.= '<h3>' . \Object\Error\Base::$error_codes[$v['errno']] . ' (' . $v['errno'] . '): <ul><li>' . implode('</li><li>', $v['error']) . '</li></ul></h3>';
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
		\Layout::$onload = "";
		\Layout::onLoad("$('#preloader').hide();");
	}
}