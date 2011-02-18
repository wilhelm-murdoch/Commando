<?php

	require_once 'phpunit.php';

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
		->addPrompts(array(
			Commando_Prompt::factory('password', 'Enter your password: ', true)->addValidator(
				Commando_Prompt_Validator_Confirm::factory()
			)
		))
		->validate($argv)
		->execute();
	} catch(Exception $Exception) {
		echo 'Error: '.$Exception->getMessage() . "\n";
	}