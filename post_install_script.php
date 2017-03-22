#!/usr/bin/php
<?php

$project_name = basename(realpath("."));
echo "Project {$project_name}\n";

/**
 * Remove directories content and optionally itself
 * 
 * @param string $dir
 * @param arary $options
 *		only_contents - whether to remove directory contents only
 *		skip_files - array of files to skip
 * @return boolean
 */
function file_remove_recursive($dir, $options = []) {
	if (is_dir($dir)) {
		$skip_files = [];
		if (!empty($options['skip_files'])) {
			$skip_files = $options['skip_files'];
			$options['only_contents'] = true;
		}
		$skip_files[] = '.';
		$skip_files[] = '..';
		$objects = scandir($dir);
		foreach ($objects as $v) {
			if (!in_array($v, $skip_files)) {
				if (filetype($dir . '/' . $v) == 'dir') {
					self::rmdir($dir . '/' . $v, $options);
				} else {
					unlink($dir . '/' . $v);
				}
			}
		}
		if (empty($options['only_contents'])) {
			return rmdir($dir);
		} else {
			return true;
		}
	}
	return false;
}
