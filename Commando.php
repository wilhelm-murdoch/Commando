<?php
	/**
	 * Commando
	 *
	 * This class acts as a wrapper around any possible argument or argument value associated with it. It is
	 * responsible for parsing, validating any specified set of command line arguments. Arguments are completely
	 * optional, but, at the very least, a valid PHP callback must be provided to the Commando::execute() method.
	 *
	 * @package Commando
	 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
	 * @license GNU Lesser General Public License v3 <http://www.gnu.org/copyleft/lesser.html>
	 * @copyright Copyright (c) 2011, Daniel Wilhelm II Murdoch
	 * @since v0.1.0a
	 * @link http://www.thedrunkenepic.com
	 */
	class Commando {
		/**
		 * Static singleton instance of class Commando.
		 * @access Private
		 * @var Object
		 * @static
		 */
		static private $singleton = null;

		/**
		 * Contains instances of class Commando_Argument.
		 * @access Private
		 * @var Array
		 * @static
		 */
		static private $arguments = array();

		/**
		 * Contains instances of class Commando_Prompt.
		 * @access Private
		 * @var Array
		 * @static
		 */
		static private $prompts = array();

		/**
		 * Contains default settings for Commando. Can be overwritten when first calling Commando::singleton($config)
		 * @access Private
		 * @var Array
		 * @static
		 */
		static private $config = array (
			'argument.pattern.help'   => '--help',                                                 // Pattern for the help switch
			'argument.pattern.switch' => '-{2}',                                                   // Pattern for an argument switch
			'argument.switch.plain'   => '--',                                                     // Plain value of an argument switch
			'argument.help.plain'     => '--help',                                                 // Plain value of the help switch
			'commando.help.pad'       => 20,                                                       // Space padding for arguments within the help section
			'argument.pattern.title'  => '[[:alnum:]-_]+',                                         // Argument pattern
			'commando.strict'         => true,                                                     // Activates STRICT validation
			'commando.version'        => 'v0.1.0a',                                                // The current version of Commando
			'commando.title'          => 'Commando',                                               // The title of this utility
			'commando.description'    => 'A framework for easily creating command line utilities.' // Describes the current utility
		);

		/**
		 * Instantiates a singleton instance of class Commando and returns it.
		 *
		 * @param Array $config Optional configuration settings which add to, or overwrite, existing self::$config settings.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando
		 * @static
		 * @uses Commando::__construct()
		 */
		static public function singleton(array $config = array()) {
			if((self::$singleton instanceof self) === false) {
				self::$singleton = new self;
				self::$config = array_merge(self::$config, $config);
			}
			return self::$singleton;
		}

		/**
		 * Will return a desired configuration item if available. If one cannot be found an exception is thrown.
		 *
		 * @param String $title The name of the configurtaion item to return.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Mixed
		 * @static
		 * @throws InvalidArgumentException
		 */
		static public function config($title) {
			if(isset(self::$config[$title]) === false) {
				throw new InvalidArgumentException("The following configuration option can not be found: {$title}");
			}
			return self::$config[$title];
		}

		/**
		 * Adds a collection of class Commando_Argument instances. These define a list of supported command
		 * line arguments and associated behaviors.
		 *
		 * @param Array $argumentCollection A collection of Commando_Argument instances.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando
		 * @static
		 * @uses Commando::addArgument()
		 */
		static public function addArguments(array $argumentCollection) {
			foreach($argumentCollection as $Argument) {
				self::addArgument($Argument);
			}
			return self::$singleton;
		}

		/**
		 * Adds a single instance of class Commando_Argument.
		 *
		 * @param Class Commando_Argument $Argument A single Commando_Argument instance.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando
		 * @static
		 * @uses Commando_Argument
		 * @uses Commando_Argument::getTitle()
		 */
		static public function addArgument(Commando_Argument $Argument) {
			self::$arguments[$Argument->getTitle()] = $Argument;
			return self::$singleton;
		}


		/**
		 * Adds a collection of class Commando_Prompt instances. Adding prompts allows the utility to
		 * query the client for additional required information.
		 *
		 * @param Array $promptCollection A collection of Commando_Prompt instances.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando
		 * @static
		 * @uses Commando::addPrompt()
		 */
		static public function addPrompts(array $promptCollection) {
			foreach($promptCollection as $Prompt) {
				self::addPrompt($Prompt);
			}
			return self::$singleton;
		}

		/**
		 * Adds a single instance of class Commando_Prompt.
		 *
		 * @param Class Commando_Prompt $Prompt A single Commando_Prompt instance.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando
		 * @static
		 * @uses Commando_Prompt
		 */
		static public function addPrompt(Commando_Prompt $Prompt) {
			self::$prompts[$Prompt->getTitle()] = $Prompt;
			return self::$singleton;
		}

		/**
		 * A convenience method which can be used to determine whether the current script has been invoked via the command
		 * line. This can be used to restrict access to current script to PHP's Cli mode.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Boolean
		 * @static
		 */
		static public function isCli() {
			return (PHP_SAPI === 'cli');
		}

		/**
		 * A simple method which automatically generates a help screen outlining the purpose of the utility as well as
		 * supported command line arguments with associated descriptions.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando
		 * @static
		 * @uses Commando_Argument::getTitle()
		 * @uses Commando_Argument::getDescription()
		 * @uses Commando::config();
		 */
		static public function showHelp() {
			$response = array (
				str_pad('Argument', self::config('commando.help.pad')).'Description',
				str_repeat('-', self::config('commando.help.pad') + 40)
			);
			foreach(self::$arguments as $Argument) {
				$response[] = str_pad($Argument->getTitle(true), self::config('commando.help.pad')).$Argument->getDescription();
			}
			$response[] = self::config('commando.title').' '.self::config('commando.version');
			$response[] = '';
			echo implode("\n", $response);
			return self::$singleton;
		}

		/**
		 * Validates the given set of arguments against bound Commando_Argument instances and their associated
		 * Commando_Argument_Value restrictions. This method will perform the following checks:
		 *
		 * 1. Removes the first $argv value as it is typically the name of the current script
		 * 2. Intercepts a possible help request and defers the action to Commando::showHelp()
		 * 3. Parses the given list of arguments and separates it into a list of known and unknown arguments
		 * 4. If Commando's 'commando.strict' setting has been set to TRUE, any unknown arguments will trigger an exception
		 * 5. If there are arguments which have been set as required, we must determine if they are missing. If they are, trigger an exception
		 * 6. Continue parsing $argv to determine a list of possible argument values
		 * 7. Validate each possible value by associating them with their assigned argument
		 * 8. Validate each argument
		 * 9. If any of the above checks fail, throw an exception
		 *
		 * @param Array $argv A list of command line arguments generated by PHP
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando
		 * @static
		 * @throws InvalidArgumentException
		 * @uses Commando::showHelp()
		 * @uses Commando::getKnownAndUnknownArguments()
		 * @uses Commando::getMissingRequiredArguments()
		 * @uses Commando::getPossibleArgumentValues()
		 * @uses Commando_Argument::bindPossibleArgumentValues()
		 * @uses Commando_Argument::validate()
		 */
		static public function validate(array $argv) {
			if($argv[0] == $_SERVER['PHP_SELF']) {
				array_shift($argv);
			}

			if(preg_match('#^'.self::config('argument.pattern.help').'$#i', $argv[0])) {
				return self::showHelp();
			}

			$knownAndUnknownArguments = self::getKnownAndUnknownArguments($argv);

			if($knownAndUnknownArguments['unknown']) {
				throw new InvalidArgumentException('The following unknown arguments have been found: '.implode(', ', $knownAndUnknownArguments['unknown']));
			}

			if($missingArguments = self::getMissingRequiredArguments($argv, $knownAndUnknownArguments['known'])) {
				throw new InvalidArgumentException('The following required arguments are missing: '.implode(', ', $missingArguments));
			}

			$possibleArgumentValues = self::getPossibleArgumentValues($argv);

			try {
				foreach($possibleArgumentValues as $argument => $possibleValues) {
					self::$arguments[$argument]->bindPossibleArgumentValues($possibleValues)->validate();
				}
			} catch(Exception $Exception) {
				throw $Exception;
			}

			return self::$singleton;
		}

		/**
		 * This will return a response corresponding to the prompt title.
		 *
		 * @param String $promptTitle Title of the desired prompt.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Array
		 * @throws InvalidArgumentException
		 * @uses Commando_Prompt::getResponse()
		 */
		public function getPrompt($promptTitle) {
			if(isset(self::$prompts[$promptTitle]) === false) {
				throw new InvalidArgumentException("Prompt could not be found: {$promptTitle}");
			}
			return self::$prompts[$promptTitle]->getResponse();
		}

		/**
		 * This will return all values associated with the specified argument title. First, it must ensure the desired
		 * argument exists. If not, it will throw an exception. It will then iterate through a known argument's bound
		 * values and return them as an array.
		 *
		 * @param String $argumentTitle Title of the desired argument.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Array
		 * @throws InvalidArgumentException
		 * @uses Commando_Argument::getValues()
		 */
		public function getArg($argumentTitle) {
			if(isset(self::$arguments[$argumentTitle]) === false) {
				throw new InvalidArgumentException("Argument could not be found: {$argumentTitle}");
			}
			return self::$arguments[$argumentTitle]->getValues();
		}

		/**
		 * After parsing the command, this method can be invoked to trigger any bound Commando_Argument observers as
		 * well as trigger a specified PHP callback. Once the callback is validated and executed, this method will pass
		 * the current singleton instance of Commando. This can be used to affect the behavior of the resource associated
		 * with the callback.
		 *
		 * In addition to a standard PHP callback, you may also pass lambda, or Closure instances, through as an alternative
		 * callback method. Please be aware, using Closures is only supported in PHP 5.3.x.
		 *
		 * This method also triggers all queued command prompts. They will be presented in the order they were assigned.
		 * Once a response has been provided for each prompt, the object will then notify any attached observers to
		 * interpret the command.
		 *
		 * Note: You must assign either arguments to this class or a callback to this method. Otherwise, there's really
		 * no point in using this library. If both requirements are not met, this method will throw an exception. A very,
		 * very sad exception.
		 *
		 * @param String, Array, Closure $callback A standard PHP callback or Closure instance.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Mixed
		 * @static
		 * @throws InvalidArgumentException
		 * @uses Commando_Argument::notify()
		 */
		static public function execute($callback = null) {
			if(is_null($callback) && self::$arguments == false) {
				throw new InvalidArgumentException('Nothing to do. Assign an argument or callback. :(');
			}

			foreach(self::$prompts as $Prompt) {
				$Prompt->show();
			}

			foreach(self::$arguments as $Argument) {
				$Argument->notify($this);
			}

			if(is_null($callback) === false) {
				if($callback instanceof Closure) {
					$Closure = new RefectionFunction($callback);
					return $Closure->invokeArgs($this);
				} elseif(is_callable($callback) === false) {
					throw new InvalidArgumentException('The specified callback can not be executed.');
				}

				return call_user_func($callback, self::$singleton);
			}
		}

		/**
		 * If this class is accessed as a string, this method will return the command which invoked it.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return String
		 * @static
		 */
		public function  __toString() {
			return implode(' ', $_SERVER['argv']);
		}

		/**
		 * This method parses $argv to determine passed argument values and will assign them to their possible
		 * Commando_Argument instance. This will return an associative array where the top-level index is the
		 * original argument switch. Assigned to this index is another array which contains the associated values.
		 *
		 * @param Array $argv A list of command line arguments generated by PHP
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Array
		 * @static
		 * @uses Commando_Argument::getTitle()
		 * @uses Commando_Argument::getTitle()
		 * @uses Commando_Argument::isValid()
		 */
		static private function getPossibleArgumentValues(array $argv) {
			$possibleArgumentValues = array();
			foreach(self::$arguments as $Argument) {
				$possibleArgumentValues[$Argument->getTitle()] = array();
				for($i = (array_search($Argument->getTitle(true), $argv) + 1); $i < count($argv); $i++) {
					if(Commando_Argument::isValid($argv[$i])) {
						break;
					}
					$possibleArgumentValues[$Argument->getTitle()][] = $argv[$i];
				}
			}
			return $possibleArgumentValues;
		}

		/**
		 * Iterates through the specified $argv values and isolates a list of possible command line arguments. It
		 * then breaks this list down into two separate associative arrays containing known, arguments which have
		 * been configured, and unknown, arguments which have NOT been configured, arguments.
		 *
		 * @param Array $argv A list of command line arguments generated by PHP
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Private
		 * @return Array
		 * @static
		 * @uses Commando_Argument::isValid()
		 * @uses Commando_Argument::stripSwitch()
		 * @uses Commando::config()
		 */
		static private function getKnownAndUnknownArguments(array $argv) {
			$knownAndUnknownArguments = array(
				'known'   => array(),
				'unknown' => array()
			);
			foreach($argv as $argument) {
				if(Commando_Argument::isValid($argument)) {
					$argumentTitle = Commando_Argument::stripSwitch($argument);
					if(isset(self::$arguments[$argumentTitle]) === false && self::config('commando.strict')) {
						$knownAndUnknownArguments['unknown'][] = $argument;
					} else {
						$knownAndUnknownArguments['known'][] = $argumentTitle;
					}
				}
			}
			return $knownAndUnknownArguments;
		}

		/**
		 * Using a list of known arguments generated from Commando::getKnownAndUnknownArguments(), this method will
		 * compare it to a list of known, REQUIRED, arguments. If any expected arguments are missing, it adds the
		 * argument to an array and returns it.
		 *
		 * @param Array $argv           A list of command line arguments generated by PHP
		 * @param Array $knownArguments A list of known, bound, arguments
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Private
		 * @return Array
		 * @static
		 * @uses Commando_Argument::isRequired()
		 * @uses Commanod_Argument::getTitle()
		 */
		static private function getMissingRequiredArguments(array $argv, array $knownArguments) {
			$missingRequiredArguments = array();
			foreach(self::$arguments as $Argument) {
				if($Argument->isRequired() && in_array($Argument->getTitle(), $knownArguments) === false) {
					$missingRequiredArguments[] = $Argument->getTitle(true);
				}
			}
			return $missingRequiredArguments;
		}
	}