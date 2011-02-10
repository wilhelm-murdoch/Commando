<?php

	require_once 'Commando.php';
	require_once 'Commando/Subject.php';
	require_once 'Commando/Argument.php';
	require_once 'Commando/Argument/Value.php';

	try {
		if(Commando::isCli() === false) {
			throw new Exception('this utility may only be accessed from the command line.');
		}

		Commando::singleton()->validate($argv)->execute();
	} catch(Exception $Exception) {
		echo 'Error: '.$Exception->getMessage();
	}