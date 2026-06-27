<?php

namespace Controller;
class Errors extends \Object\Controller {

	public $title = 'Error Handler';

	/**
	 * Error action
	 */
	public function actionError() {
		// if we already rendered and have extra errors we would need to review logs for that
		if (\Application::get('flag.global.__already_rendered')) {
			return;
		}
		$messages = [];
		$all_messages = [];
		$http_status_code = null;
		// show human readable messages first
		if (count(\Object\Error\Base::$errors) > 0) {
			// throw error in json format if ajax is set
			if (\Application::get('flag.global.__ajax') || \Application::get('flag.global.__is_api')) {
				$http_status_code = null;
				foreach (\Object\Error\Base::$errors as $k => $v) {
					if (isset(\Helper\Constant\HTTPConstants::STATUSES[$v['errno']])) {
						$http_status_code = $v['errno'];
					}
					foreach ($v['error'] as $v2) {
						$messages[] = $v2;
						if (\Debug::$debug) {
							$messages[] = 'Location: ' . $v['file'] . ':' . $v['line'];
						}
					}
				}
				// render through response object
				\Helper\HTTPResponse::output($http_status_code ?? \Helper\Constant\HTTPConstants::Status500InternalServerError, 'application/json', [
					'success' => false,
					'error' => $messages,
					'http_status_code' => $http_status_code ?? 419,
				]);
				return;
			}
			// render html
			foreach (\Object\Error\Base::$errors as $v) {
				if ($v['errno'] == -1 || isset(\Helper\Constant\HTTPConstants::STATUSES[$v['errno']])) {
					foreach ($v['error'] as $v2) {
						$messages[] = $v2;
					}
				}
				if (isset(\Helper\Constant\HTTPConstants::STATUSES[$v['errno']])) {
					$http_status_code = $v['errno'];
				}
				foreach ($v['error'] as $v2) {
					$all_messages[] = $v2;
				}
			}
			if (empty($messages)) {
				$messages[] = loc('NF.Status.InternalServerError', 'Internal Server Error') . ': ' . \Format::id(500);
			}
			if (empty($all_messages)) {
				$all_messages[] = loc('NF.Status.InternalServerError', 'Internal Server Error') . ': ' . \Format::id(500);
			}
			// add data to firewall
			$firewalls = \Object\ACL\Resources::getStatic('firewalls', 'primary');
			if (!empty($firewalls)) {
				call_user_func_array($firewalls['method'], [\Request::ip(), $all_messages]);
			}
		}
		// default to 500 if not set
		if (!$http_status_code) {
			$http_status_code = \Helper\Constant\HTTPConstants::Status500InternalServerError;
		}
		// and we need to provide statu code decription first
		array_unshift($messages, $http_status_code . ' - ' . \Helper\Constant\HTTPConstants::STATUSES[$http_status_code]['name']);
		// render
		http_response_code($http_status_code);
		// errors
		\Layout::addMessage($messages, DANGER);
		// template
		echo \Template::renderStatic(\Template::PHP, '/Numbers/Templates/Common/View/ErrorXXX.template.php', [
			'errors' => \Application::get('flag.error.show_full') ? \Object\Error\Base::$errors : [],
			'debug' => \Debug::$debug ?? false,
			'iframe' => \Application::get('application.template.name') == 'iframe',
		]);
		// clear our onload
		\Layout::$onload = "";
		\Layout::onLoad("$('#preloader').hide();");
	}

	/**
	 * Maintenance action
	 */
	public function actionMaintenance() {
		// if we already rendered and have extra errors we would need to review logs for that
		if (\Application::get('flag.global.__already_rendered')) {
			return;
		}
		\Layout::addMessage(\Application::get('maintenance.message'), DANGER);
		\Layout::setTemplateSettings(['default'], [
			'menu' => 'no_menu',
			'footer' => 'no_footer',
			'title' => 'no_title'
		]);
		// clear our onload
		\Layout::$onload = "";
		\Layout::onLoad("$('#preloader').hide();");
	}
}
