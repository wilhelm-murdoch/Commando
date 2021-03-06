<?php
	/**
	 * Commando_Argument
	 *
	 * Represents an expected command line argument for a utility. Arguments, and their associated values, are used as utility
	 * behavior modifiers.
	 *
	 * This class also extends Commando_Subject, which implements PHP's SplSubject pattern. This means you can attach observers
	 * to an argument to even further affect your utility's behavior. All assigned observers are invoked once the utility is
	 * executed from the Commmando instance from within Commando_Argument::bindPossibleArgumentValues().
	 *
	 * NOTE: All attached observer classes MUST implement PHP's SplObserver pattern.
	 *
	 * @package Commando
	 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
	 * @license GNU Lesser General Public License v3 <http://www.gnu.org/copyleft/lesser.html>
	 * @copyright Copyright (c) 2011, Daniel Wilhelm II Murdoch
	 * @since v0.1.0a
	 * @link http://www.thedrunkenepic.com
	 */
	class Commando_Argument extends Commando_Subject {
		/**
		 * Determines whether this is a required argument.
		 * @access Private
		 * @var Boolean
		 */
		private $isRequired;

		/**
		 * The actual command line representation of an argument (sans Command::config('argument.switch.plain')). Eg: --filter ".php .xml .html"
		 * @access Private
		 * @var String
		 */
		private $title;

		/**
		 * The description of this argument. This value, along with Command_Argument::$title, are used when generating the help screen.
		 * @access Private
		 * @var String
		 */
		private $description;

		/**
		 * A collection of Command_Argument_Value intances which are bound to this argument.
		 * @access Private
		 * @var Array
		 */
		private $values;

		/**
		 * Instantiates class and defines instance variables.
		 *
		 * @param String $title       The command line representation of an argument.
		 * @param String $description Used to describe the purpose of this argument.
		 * @param Boolean $isRequired Determines whether this is a required value
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Commando_Argument_Value
		 */
		public function __construct($title, $description, $isRequired = false) {
			parent::__construct();
			$this->title       = strtolower($title);
			$this->description = $description;
			$this->isRequired  = $isRequired;
			$this->values      = array();
		}

		/**
		 * Factory pattern which returns a brand new instance of this class
		 *
		 * @param String $title       The command line representation of an argument.
		 * @param String $description Used to describe the purpose of this argument.
		 * @param Boolean $isRequired Determines whether this is a required value
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando_Argument
		 * @static
		 * @uses Commando::__construct()
		 */
		static public function factory($title, $description, $isRequired = false) {
			return new self($title, $description, $isRequired);
		}

		/**
		 * Returns Commando_Argument::$title. If $asSwitch is set to TRUE, this method will prepend
		 * Commando::config('argument.switch.plain') to the return value.
		 *
		 * @param Boolean $asSwtich Prepends config value 'argument.switch.plain' to return string.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return String
		 * @uses Commando::config()
		 */
		public function getTitle($asSwitch = false) {
			return $asSwitch ? Commando::config('argument.switch.plain').$this->title : $this->title;
		}

		/**
		 * Returns Commando_Argument::$description.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return String
		 */
		public function getDescription() {
			return $this->description;
		}

		/**
		 * Returns TRUE of the current instance of this class is required; FALSE if it isn't.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Boolean
		 */
		public function isRequired() {
			return (bool) $this->isRequired;
		}

		/**
		 * Removes the argument switch from the specified title.
		 *
		 * @param String $argumentTitle The value to modify.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return String
		 * @static
		 * @uses Commando::config()
		 */
		static public function stripSwitch($argumentTitle) {
			return strtolower(preg_replace('#^'.Commando::config('argument.pattern.switch').'#i', '', $argumentTitle));
		}

		/**
		 * This method will match the specified argument title against defined regex patterns. Returns TRUE if the
		 * value passes validation or FALSE if it fails.
		 *
		 * @param String $argumentTitle The string to validate.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Boolean
		 * @static
		 * @uses Commando::config()
		 */
		static public function isValid($argumentTitle) {
			return (bool) preg_match('#^'.Commando::config('argument.pattern.switch').Commando::config('argument.pattern.title').'$#i', $argumentTitle);
		}

		/**
		 * Iterates through the specified array of possible argument values and matches them to associated instances
		 * of Commando_Argument_Value. The association is defined by a simple one-to-one match of array indexes.
		 *
		 * For instance:
		 *
		 * $possibleArgumentValues[0] will be assigned to Commando_Argument::$values[0]
		 * $possibleArgumentValues[1] will be assigned to Commando_Argument::$values[1]
		 * ...
		 * $possibleArgumentValues[50] will be ignored since there is no instance Commando_Argument_Value assigned to
		 * this index within Commando_Argument::$values.
		 *
		 * It is also important to note that invoking Commando_Argument_Value::setValue() triggers any bound observers
		 * to the current value.
		 *
		 * NOTE: Validation of mapped values does not occur here, but rather from within Commando_Argument::validate().
		 *
		 * @param Array $possibleArgumentValues An array containing possible values to be mapped.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando_Argument
		 * @uses Commando_Argument_Value::setValue()
		 */
		public function bindPossibleArgumentValues(array $possibleArgumentValues) {
			foreach($possibleArgumentValues as $index => $possibleValue) {
				if($this->has('Commando_Argument_Value_Multi')) {
					$this->values[0]->setValue($possibleValue);
				} elseif(isset($this->values[$index])) {
					$this->values[$index]->setValue($possibleValue);
				}
			}
			return $this;
		}

		/**
		 * Iterates through the defined collection of Commando_Argument_Value class instances and ensures they meet
		 * any configured requirements. For instance, if an encountered value is marked as required, but has no value
		 * assigned ot it, an exception is thrown. Also, this method will fail if a value cannot be correctly validated
		 * against its defined Commando_Argument_Value::$valuePattern.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Null
		 * @throws InvalidArgumentException
		 * @uses Commando_Argument_Value::isRequired()
		 * @uses Commando_Argument_Value::hasValue()
		 * @uses Commando_Argument_Value::getTitle()
		 * @uses Commando_Argument_Value::isValid()
		 */
		public function validate() {
			foreach($this->values as $Value) {
				if($Value->isRequired() && $Value->hasValue() === false) {
					throw new InvalidArgumentException('Missing argument value for: '.$this->getTitle(true));
				}
				if($Value->isValid($Value->getValue()) === false) {
					throw new InvalidArgumentException('Invalid argument value for '.$this->getTitle(true).": {$Value}");
				}
			}
		}

		/**
		 * Adds a collection of Commando_Argument_Value_* class instances. These define a list of
		 * supported value types for an argument and associated behaviors.
		 *
		 * @param Array $valueCollection A collection of Commando_Argument_Value_* instances.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando_Argument
		 * @uses Commando_Argument::addValue()
		 */
		public function addValues(array $valueCollection) {
			foreach($valueCollection as $Value) {
				$this->addValue($Value);
			}
			return $this;
		}

		/**
		 * Adds a singe instance of class Commando_Argument_Value_*.
		 *
		 * NOTE: If Commando_Argument_Value_Multi is added, it must be the ONLY value object assigned. Otherwise it will
		 * throw an InvalidArgumentException.
		 *
		 * @param Array $Value A single intance of class Commando_Argument_Value_*.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando_Argument
		 * @throws InvalidArgumentException
		 * @uses Commando_Argument_Value_Single
		 * @uses Commando_Argument_Value_Multi
		 */
		public function addValue(Commando_Argument_Value $Value) {
			if($Value instanceof Commando_Argument_Value_Single) {
				if($this->has('Commando_Argument_Value_Multi')) {
					throw new InvalidArgumentException("Multi value already assigned to argument: {$this}");
				}
				$this->values[] = $Value;
			} elseif($Value instanceof Commando_Argument_Value_Multi) {
				if($this->has('Commando_Argument_Value_Single')) {
					throw new InvalidArgumentException("Single value already assigned to argument: {$this}");
				}
				$this->values[] = $Value;
			} else {
				throw new InvalidArgumentException("Unknown value type of ".get_class($Value)." added to: {$this}");
			}
			return $this;
		}

		/**
		 * Used to determine whether the current argument contains value types of the specified class. Returns TRUE
		 * if a matching value type is found. FALSE if a match cannot be found.
		 *
		 * @param String $className The name of the class to match.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Boolean
		 */
		public function has($className) {
			foreach($this->values as $Value) {
				if($Value instanceof $className) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Returns all current values associated with the current argument.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Array
		 * @uses Commando_Argument_Value::getValue()
		 */
		public function getValues() {
			if($this->has('Commando_Argument_Value_Multi')) {
				return $this->values[0]->getValue();
			}
			$argumentValues = array();
			foreach($this->values as $Value) {
				$argumentValues[] = $Value->getValue();
			}
			return $argumentValues;
		}

		/**
		 * If this class is accessed as a string, this method will return its command line representation.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return String
		 * @static
		 * @uses Commando_Argument::getTitle()
		 */
		public function  __toString() {
			return $this->getTitle(true);
		}
	}