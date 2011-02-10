<?php

	class Commando {
		const __ARGUMENT_PATTERN_SWITCH = '-{2}';
		const __ARGUMENT_PATTERN_TITLE = '[[:alnum:]-_]+';
		const __ARGUMENT_HELP_SWITCH = '?';

		static private $singleton = null;
		static private $arguments = array();
		static private $callback = null;

		static public function singleton($callback = null) {
			if((self::$singleton instanceof self) === false) {
				if(is_null($callback)) {
					throw new InvalidArgumentException('a callback must be bound to the command client');
				}
				self::$singleton = new self;
				self::$callback = $callback;
			}
			return self::$singleton;
		}

		static public function isCli() {
			return (PHP_SAPI === 'cli');
		}

		static private function isArgument($argument) {
			return (bool) preg_match('#^'.self::__ARGUMENT_PATTERN_SWITCH.self::__ARGUMENT_PATTERN_TITLE.'$#i', $argument);
		}

		static private function stripArgumentSwitch($argument) {
			return strtolower(preg_replace('#^'.self::__ARGUMENT_PATTERN_SWITCH.'#i', '', $argument));
		}

		static public function getArgumentValue($argument) {
			return isset(self::$arguments[$argument]) ? self::$arguments[$argument]->getValue() : null;
		}

		static public function addArgument($arguments) {
			if(is_array($arguments)) {
				foreach($arguments as $Argument) {
					if($Argument instanceof Argument) {
						self::$arguments[$Argument->title] = $Argument;
					}
				}
			} else {
				if($argument instanceof Argument) {
					self::$arguments[$Argument->title] = $Argument;
				}
			}
			return self::$singleton;
		}

		static public function showHelp() {
			echo 'this is the help section';
			return self::$singleton;
		}

		static public function validate(array $arguments) {
			if($arguments[0] == $_SERVER['PHP_SELF']) {
				array_shift($arguments);
			}

			if($arguments[0] == self::__ARGUMENT_HELP_SWITCH) {
				return self::showHelp();
			}

			$knownArguments  = array();
			$unkownArguments = array();
			foreach($arguments as $argument) {
				if(self::isArgument($argument)) {
					$argumentTitle = self::stripArgumentSwitch($argument);
					if(isset(self::$arguments[$argumentTitle])) {
						$knownArguments[] = $argumentTitle;
					} else {
						$unknownArguments[] = $argument;
					}
				}
			}

			if($unknownArguments) {
				throw new InvalidArgumentException('Unknown arguments detected');
			}

			// are all required arguments present?
			$missingArguments = array();
			foreach(self::$arguments as $Argument) {
				if($Argument->isRequired() && in_array($Argument->title, $knownArguments) === false) {
					$missingArguments[] = $Argument->title;
				}
			}

			if($missingArguments) {
				throw new InvalidArgumentException('missing required argument: '.implode(', ', $missingArguments));
			}

			// do all arguments with required values have them?
			$missingValues = array();
			foreach($arguments as $index => $argument) {
				if(self::isArgument($argument)) {
					$argumentTitle = self::stripArgumentSwitch($argument);
					if(isset(self::$arguments[$argumentTitle])) {
						if(self::$arguments[$argumentTitle]->isValueRequired()) {
							if(isset($arguments[$index++]) === false) {
								$missingValues[] = $argumentTitle;
							} else {
								self::$arguments[$argumentTitle]->setValue($arguments[$index++]);
							}
						}
					}
				}
			}

			if($missingValues) {
				throw new InvalidArgumentException('missing required values for: '.implode(', ', $missingValues));
			}

			return self::$singleton;
		}

		static public function execute() {
			foreach(self::$arguments as $Argument) {
				$Argument->notify();
			}
			return call_user_func(self::$callback, self::$instance);
		}

		static public function  __toString() {
			return implode(' ', $_SERVER['argv']);
		}
	}