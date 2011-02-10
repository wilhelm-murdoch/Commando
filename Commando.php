<?php

	class Commando {
		static private $singleton = null;
		static private $arguments = array();
		static private $config = array (
			'argument.pattern.help'   => '\?',
			'argument.pattern.switch' => '-{2}',
			'argument.switch.plain'   => '--',
			'argument.help.plain'     => '?',
			'argument.pattern.title'  => '[[:alnum:]-_]+',
			'commando.strict'         => true
		);

		static public function singleton(array $config = array()) {
			if((self::$singleton instanceof self) === false) {
				self::$singleton = new self;
				self::$config = array_merge(self::$config, $config);
			}
			return self::$singleton;
		}

		static public function config($path) {
			if(isset(self::$config[$path]) === false) {
				throw new InvalidArgumentException("The following configuration option can not be found: {$path}");
			}
			return self::$config[$path];
		}

		static public function addArgument($arguments) {
			if(is_array($arguments)) {
				foreach($arguments as $Argument) {
					if($Argument instanceof Commando_Argument) {
						self::$arguments[$Argument->getTitle()] = $Argument;
					}
				}
			} else {
				if($arguments instanceof Commando_Argument) {
					self::$arguments[$arguments->getTitle()] = $arguments;
				}
			}
			return self::$singleton;
		}

		static public function isCli() {
			return (PHP_SAPI === 'cli');
		}

		static public function showHelp() {
			echo 'help support';
			return self::$singleton;
		}

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
				throw new InvalidArgumentException('Required values for the following arguments are missing: '.implode(', ', $missingArguments));
			}

			$possibleArgumentValues = self::getPossibleArgumentValues($argv);

			try {
				foreach($possibleArgumentValues as $argument => $values) {
					self::$arguments[$argument]->bindPossibleArgumentValues($values)->validate();
				}
			} catch(Exception $Exception) {
				throw $Exception;
			}

			return self::$singleton;
		}

		static public function execute($callback = null) {
			foreach(self::$arguments as $Argument) {
				$Argument->notify();
			}
			return is_null($callback) === false ? call_user_func(self::$callback, self::$instance) : null;
		}

		static public function  __toString() {
			return implode(' ', $_SERVER['argv']);
		}

		static private function getPossibleArgumentValues(array $argv) {
			$possibleArgumentValues = array();
			foreach(self::$arguments as $Argument) {
				$startingIndex = array_search($Argument->getTitle(true), $argv) + 1;
				for($i = $startingIndex; $i < count($argv); $i++) {
					if(Commando_Argument::isValid($argv[$i])) {
						break;
					}
					$possibleArgumentValues[$Argument->getTitle()][] = $argv[$i];
				}
			}
			return $possibleArgumentValues;
		}

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

		static private function getMissingRequiredArguments(array $argv, array $knownArguments) {
			$missingRequiredArguments = array();
			foreach(self::$arguments as $Argument) {
				if($Argument->isRequired() && in_array($Argument->getTitle(), $knownArguments) === false) {
					$missingRequiredArguments[] = $Argument->getTitle();
				}
			}
			return $missingRequiredArguments;
		}
	}