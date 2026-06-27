<?php

namespace Controller;
class HealthCheck extends \Object\Controller {

	public $title = 'Health Check';

	public function actionIndex() {
		\Layout::renderAs('OK', 'text/plain');
	}

	public function actionSystemStatus() {
		$table = '<table class="table table-striped">';
			$table .= '<tr><th width="25%">' . loc('NF.Form.ApacheServerStatus', 'Apache Server Status:') . '</th><td width="75%"><b>' . loc('NF.Form.OK', 'OK') . '</b></td></tr>';
			$table .= '<tr><th width="25%">' . loc('NF.Form.DatabaseServerStatus', 'Database Server Status:') . '</th><td width="75%"><b>' . (\Db::backendInitializedStatic('default') ? loc('NF.Form.OK', 'OK') : loc('NF.Form.Fail', 'Fail')) . '</b></td></tr>';
		$table .= '</table>';
		return \HTML::segment([
			'type' => 'primary',
			'header' => [
				'title' => loc('NF.Form.SystemStatusColon', 'System Status:'),
			],
			'value' => $table,
		]);
	}
}
