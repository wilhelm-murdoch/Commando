<?php

	// Perpetual prompt example
	// Written By Nick Shepherd <gene.shepherd@gmail.com>

	require_once 'phpunit.php';
	
	global $state;
	
	$state = true;

	try {
		
		if(Commando::isCli() === false) {
			throw new Exception('this utility may only be accessed from the command line.');
		}

		while($state) {
			
			Commando::singleton()->addPrompts(array(
				Commando_Prompt::factory('commando', 'commando > ', true)
			))
			->execute('executeCommand');
			
		}
		
	} catch(Exception $Exception) {
		echo 'Error: '.$Exception->getMessage() . "\n";
	}
	
	
	function executeCommand($commando)
	{
		global $state;
		
		if($commando->getPrompt('commando') == 'exit')
			$state = false;
		
		print $commando->getPrompt('commando') . "\n";
	}