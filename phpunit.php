<?php

	function autoLoad($className) {
		$classPath = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.str_replace('_', DIRECTORY_SEPARATOR, $className).'.php';
		echo $classPath."\n";
		if(file_exists($classPath)) {
			require_once $classPath;
			return true;
		}
		return false;
	}

	spl_autoload_register('autoLoad');