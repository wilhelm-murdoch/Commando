<?php
	/**
	 * PHPUnit Bootstrap
	 *
	 * This file is used by PHPUnit to bootstrap the testing suite. This should include a simple autoloader as well as any
	 * constants, or other configuration settings, which are required by the test suite.
	 *
	 * @package Commando
	 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
	 * @license GNU Lesser General Public License v3 <http://www.gnu.org/copyleft/lesser.html>
	 * @copyright Copyright (c) 2011, Daniel Wilhelm II Murdoch
	 * @since v0.1.0a
	 * @link http://www.thedrunkenepic.com
	 */
	function autoLoad($className) {
		$classPath = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.str_replace('_', DIRECTORY_SEPARATOR, $className).'.php';
		if(file_exists($classPath)) {
			require_once $classPath;
			return true;
		}
		return false;
	}

	spl_autoload_register('autoLoad');