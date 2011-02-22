<?php
	class Commando_PerpetualPrompt extends Commando_Subject {
		
		/**
		 * Prompt Status Constants
		 * @access Public
		 * @var Constant
		 * @static
		 */
		const STATUS_EXIT = 0;
		const STATUS_ACTIVE = 1;
		const STATUS_PROCESSING = 2;
		
		/**
		 * State of the current Perpetual prompt.
		 * STATUS_EXIT: will exit after the current command is completed
		 * STATUS_ACTIVE: will give another prompt after the current command is completed
		 * STATUS_PROCESSING: used while the current command is being processed
		 * @access Private
		 * @var Integer
		 * @static
		 */
		static private $state = self::STATUS_ACTIVE;
		
		/**
		 * The Prompt object that is used for the Perpetual Prompt.
		 * @access Private
		 * @var Commando_Prompt
		 * @static
		 */
		static private $prompt = null;
		
		/**
		 * The text to be used for the next prompt.
		 * @access Private
		 * @var String
		 * @static
		 */
		static private $prompt_text = ' > ';
		
		/**
		 * Instantiates an instance of the Commando Prompt and sets the state of Perpetual Prompt to STATUS_ACTIVE
		 *
		 * @param String $id The id for the prompt to be created for the perpetual prompt
		 * @param String $prompt The string to be used as the prompt
		 * @param String $callback The name of a callback function to be called when the prompt response has been submitted
		 * @author Nick Shepherd <gene.shepherd@gmail.com>
		 * @access Public
		 * @return Void
		 * @static
		 */
		static public function factory($id, $prompt, $callback) {
			
			// On Start Method called to build anything needed to prepare the Perpetual Prompt
			self::onStart();
			
			// Setting the prompt text (allows the prompting text to be changed at any time)
			self::$prompt_text = $prompt;
			
			// Initialize the Command_Prompt object if it hasn't already been created
			if(self::$prompt == null)
			{
				self::$prompt = new Commando_Prompt($id, self::$prompt_text, true);
			}
			
			// Loop to ensure that we stay within the Prompt state machine until self::$state is set to self::STATUS_EXIT
			while(self::$state == self::STATUS_ACTIVE)
			{
				// Set the prompt text from the current prompt state
				self::$prompt->setMessage(self::$prompt_text);
				
				// Display the prompt
				Commando::singleton()->addPrompts(array(self::$prompt))->execute($callback);
			}
			
			// Cleanup Method called once the self::$state is set to self::STATUS_EXIT
			self::onExit();
		}
		
		/**
		 * Sets the text for the next prompt displayed
		 *
		 * @param String $prompt Text for the next prompt displayed
		 * @author Nick Shepherd <gene.shepherd@gmail.com>
		 * @access Public
		 * @return Void
		 * @static
		 */
		static public function setPrompt($prompt)
		{
			if(!is_string($prompt))
				throw new Exception('Prompt must be a string.');
				
			self::$prompt_text = $prompt;
		}
		
		/**
		 * Initialization Method, called before the first prompt is shown.
		 *
		 * @author Nick Shepherd <gene.shepherd@gmail.com>
		 * @access Protected
		 * @return Void
		 * @static
		 */
		static protected function onStart()
		{
			print "Starting Prompt\n";
		}
		
		/**
		 * Cleanup method, called after self::$status is set to self::STATUS_EXIT
		 *
		 * @author Nick Shepherd <gene.shepherd@gmail.com>
		 * @access Protected
		 * @return Void
		 * @static
		 */
		static protected function onExit()
		{
			print "Exiting Prompt\n";
		}
		
		/**
		 * Returns the current state of the Perpetual Prompt
		 *
		 * @author Nick Shepherd <gene.shepherd@gmail.com>
		 * @access Public
		 * @return Integer
		 * @static
		 */
		static public function getState()
		{
			return self::$state;
		}
		
		/**
		 * Sets the state of the current Perpetual Prompt
		 *
		 * @param Integer $state Acceptable values are self::STATUS_EXIT, self::STATUS_ACTIVE or self::STATUS_PROCESSING
		 * @author Nick Shepherd <gene.shepherd@gmail.com>
		 * @access Public
		 * @return Void
		 * @static
		 */
		static public function setState($state)
		{
			if($state == self::STATUS_EXIT || $state == self::STATUS_ACTIVE || $state == self::STATUS_PROCESSING)
			{
				self::$state = $state;
			}
		}
		
	}
	