<?php
	class Commando_PerpetualPrompt extends Commando_Subject {
		static $state = 1;
		static $current_command = null;
		static $prompt = null;
		
		static public function factory($id, $prompt, $callback) {
			self::onStart();
			
			if(self::$prompt == null)
			{
				self::$prompt = new Commando_Prompt($id, $prompt, true);
			}
			
			while(self::$state == 1)
			{
				Commando::singleton()->addPrompts(array(self::$prompt))->execute($callback);
			}
			
			self::onExit();
		}
		
		static public function setCommand($command)
		{
			self::$current_command = $command;
		}
		
		static public function onStart()
		{
			print "Starting Prompt\n";
		}
		
		static public function onExit()
		{
			print "Exiting Prompt\n";
		}
		
		public function getState()
		{
			return ($this->state);
		}
		
	}
	