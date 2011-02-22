<?php

	// Perpetual prompt example
	// Written By Nick Shepherd <gene.shepherd@gmail.com>

	require_once 'phpunit.php';

	try {
		
		if(Commando::isCli() === false) {
			throw new Exception('this utility may only be accessed from the command line.');
		}

		$prompt = Commando_PerpetualPrompt::factory('commando', 'commando > ', 'processInput');
		
		
	} catch(Exception $Exception) {
		echo 'Error: '.$Exception->getMessage() . "\n";
	}
	
	function processInput($commando)
	{
		$cmd = $commando->getPrompt('commando')->getResponse();
		
		if($cmd == 'exit')
			Commando_PerpetualPrompt::$state = 0;
			
		print $commando->getPrompt('commando')->getResponse() . "\n";
	}