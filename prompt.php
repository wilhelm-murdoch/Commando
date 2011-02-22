<?php

	// Perpetual prompt example
	// Written By Nick Shepherd <gene.shepherd@gmail.com>

	require_once 'phpunit.php';

	try {
		
		if(Commando::isCli() === false) {
			throw new Exception('this utility may only be accessed from the command line.');
		}

		Commando_PerpetualPrompt::execute('commando', 'commando > ', 'processInput');
		
		
	} catch(Exception $Exception) {
		echo 'Error: '.$Exception->getMessage() . "\n";
	}
	
	function processInput($commando)
	{
		$cmd = $commando->getPrompt('commando')->getResponse();
		
		if($cmd == 'exit')
			Commando_PerpetualPrompt::setState( Commando_PerpetualPrompt::STATUS_EXIT );
			
		if($cmd == 'prompt')
			Commando_PerpetualPrompt::setPrompt( ' ~new prompt~ > ');
			
		print $commando->getPrompt('commando')->getResponse() . "\n";
	}