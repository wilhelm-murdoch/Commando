<?php

	require_once 'Commando.php';
	require_once 'Commando/Support.php';
	require_once 'Commando/Subject.php';
	require_once 'Commando/Argument.php';
	require_once 'Commando/Argument/Value.php';
	require_once 'Commando/Argument/Value/Single.php';
	require_once 'Commando/Argument/Value/Multi.php';

	try {
		if(Commando::isCli() === false) {
			throw new Exception('this utility may only be accessed from the command line.');
		}

		Commando::singleton()->addArgument(
			Commando_Argument::factory('directory', null, true)->addValues(array(
				Commando_Argument_Value_Single::factory(),
				Commando_Argument_Value_Single::factory()
			))
		)
		->validate($argv)
		->execute();
	} catch(Exception $Exception) {
		echo 'Error: '.$Exception->getMessage();
	}