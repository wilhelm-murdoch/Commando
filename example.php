<?php

	require_once 'Commando.php';
	require_once 'Commando/Support.php';
	require_once 'Commando/Subject.php';
	require_once 'Commando/Argument.php';
	require_once 'Commando/Argument/Value.php';

	try {
		if(Commando::isCli() === false) {
			throw new Exception('this utility may only be accessed from the command line.');
		}

		class Test implements Commando_Support {
			static public function commando(Commando $Commando) {
				foreach($Commando->getArg('directory') as $directory) {
					if(is_dir($directory)) {
						echo "Iterating Directory: {$directory}\n";
						foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::CHILD_FIRST) as $File) {
							echo $File->getPathName()."\n";
						}
						echo "\n\n";
					}
				}
			}
		}

		Commando::singleton()->addArgument(
			Commando_Argument::factory('directory', null, true)->addValue(array(
				Commando_Argument_Value::factory(true),
				Commando_Argument_Value::factory(),
				Commando_Argument_Value::factory(),
				Commando_Argument_Value::factory()
			))
		)
		->validate($argv)
		->execute(array('Test', 'commando'));

	} catch(Exception $Exception) {
		echo 'Error: '.$Exception->getMessage();
	}