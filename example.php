<?php

	require_once 'Commando.php';
	require_once 'Commando/Subject.php';
	require_once 'Commando/Argument.php';
	require_once 'Commando/Argument/Value.php';

	try {
		if(Commando::isCli() === false) {
			throw new Exception('this utility may only be accessed from the command line.');
		}

		Commando::singleton()->addArgument(array(
			Commando_Argument::factory('directory', 'the directory to parse', true)->addValue(
				Commando_Argument_Value::factory(true, true)
			),
			Commando_Argument::factory('filter', 'a list of extensions to filter')->addValue(
				Commando_Argument_Value::factory(true, '(php)')
			)
		))
		->validate($argv)
		->execute();
	} catch(Exception $Exception) {
		echo 'Error: '.$Exception->getMessage();
	}