<?php

	require_once 'Commando.php';
	require_once 'Commando/Subject.php';
	require_once 'Commando/Argument.php';
	require_once 'Commando/Argument/Value.php';

	/**
	 * @todo add Argument_Value class to bind multiple values to an Argument
	 * @todo add Subject/Observer support to attach decorators to Arguments
	 */

	if(Commando::isCli() === false) {
		die('must be in PHP cli');
	}

	Commando::singleton(array(new CommandTest, 'run'))->addArgument(array(
		Commando_Argument::factory('directory', true, true)->attach(new SubjectTest),
		Commando_Argument::factory('filter', true, true, '(php|js|png|gif)')->attach(new SubjectTest),
	))->validate($argv)->execute();

	if(Commando::isCli() === false) {
		die('must be in PHP cli');
	}

	//php cli.php --filter one two three --directory "./"

	/**
	 * 1. Invoke singleton pattern to retrieve single instance of class Command.
	 * 2. Add optional/required command line arguments to the Command.
	 * 3. Each argument may have as many argument values as needed.
	 * 4. Arguments implement SplSubject, so you may bind as many observers, of any kind, as you like.
	 * 5. Begin the validation process against supplied command line arguments ($_SERVER['argv']).
	 * 6. If all goes well we now execute the entire command while passing a valid PHP callback as a parameter.
	 * 7. All observes are notified.
	 * 8. Supplied PHP callback is invoked.

	try {
		if(Command::isCli() === false) {
			die('must be in PHP cli');
		}

		Command::singleton()->addArgument(array(
			Command_Argument::factory($argumentTitle, $isRequired)->addValue(
				Command_Argument_Value::factory($isRequired, $validationPattern
			))
			->attach(new ClassWhichObservesArgument),
			Command_Argument::factory($argumentTitle, $isRequired)->addValue(array(
				Command_Argument_Value::factory($isRequired, $validationPattern),
				Command_Argument_Value::factory($isRequired, $validationPattern)
			))
			->attach(array(
				new ClassWhichObservesArgument,
				new ClassWhichObservesArgument,
				new ClassWhichObservesArgument
			)),
		))
		->validate($_SERVER['argv'])
		->execute(array(CallbackObject, CallbackMethod));
	} catch(Exception $Exception) {
		echo $Exception->getMessage();
	}