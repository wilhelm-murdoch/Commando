<?php
	/**
	 * Commando_Argument_Value_Multi
	 *
	 * This value type may be used only once per argument and it must also be the only value type assigned. A value of
	 * this type allows multiple values to be passed through an argument. If a value pattern is defined, all associated
	 * values will be matched against it. You may also limit the number of accepted values for a given argument by
	 * specifying it within the constructor or factory methods using the $valueLimit parameter.
	 *
	 * As this is a sub class of Commando_Argument_Value, it derives all of its properties and behaviors. This means
	 * you may also attach observers to it. For observers, the same rules apply as with all other value types.
	 *
	 * @package Commando
	 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
	 * @license GNU Lesser General Public License v3 <http://www.gnu.org/copyleft/lesser.html>
	 * @copyright Copyright (c) 2011, Daniel Wilhelm II Murdoch
	 * @since v0.1.0a
	 * @link http://www.thedrunkenepic.com
	 */
	class Commando_Argument_Value_Multi extends Commando_Argument_Value {
		/**
		 * Determines the number of allowable values which can be assined to this class. Defaults to NULL which
		 * represents an unbounded number of values.
		 * @access Private
		 * @var Null, Integer
		 */
		private $valueLimit;

		/**
		 * Instantiates class and defines instance variables.
		 *
		 * @param Boolean $isRequired       Determines whether this is a required value
		 * @param Null, Integer $valueLimit Determines the number of allowed values
		 * @param String  $valuePattern     Used to validate specified values
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Commando_Argument_Value
		 */
		public function __construct($isRequired = false, $valueLimit = null, $valuePattern = null) {
			parent::__construct($isRequired, $valuePattern);
			$this->value = array();
			$this->valueLimit = $valueLimit;
		}

		/**
		 * Factory pattern which returns a brand new instance of this class
		 *
		 * @param Boolean $isRequired   Determines whether this is a required value
		 * @param Null, Integer $valueLimit Determines the number of allowed values
		 * @param String  $valuePattern Used to validate specified values
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando_Argument_Value
		 * @static
		 * @uses Commando::__construct()
		 */
		static public function factory($isRequired = false, $valueLimit = null, $valuePattern = null) {
			return new self($isRequired, $valueLimit, $valuePattern);
		}

		/**
		 * A slightly modified version of Commando_Argument_Value::isValid() which adds support for multi
		 * value types.  Overloaded from Commando_Argument_Value parent class to support multiple values.
		 *
		 * @param Array $values An array of argument values to validate.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Boolean
		 */
		public function isValid(array $values) {
			foreach($values as $value) {
				if(parent::isValid($value) === false) {
					return false;
				}
			}
			return true;
		}

		/**
		 * Assigns a value to class property $this->value array. Will also notify all attached observer objects.
		 * Overloaded from Commando_Argument_Value parent class to support multiple values.
		 *
		 * @param String $value The value to assign to current instance of this class.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object $this
		 * @throws InvalidArgumentException
		 * @uses Commando_Subject::notify()
		 */
		public function setValue($value) {
			if($this->valueLimit && count($this->value) >= $this->valueLimit) {
				throw new InvalidArgumentException("Maximum number of values reached.");
			}
			$this->value[] = $value;
			$this->notify($this);
			return $this;
		}

		/**
		 * Magic method which returns the current value assigned to an instance of this class when it is accessed as a string.
		 * Overloaded from Commando_Argument_Value parent class to support multiple values.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return String
		 */
		public function  __toString() {
			$return = '';
			foreach($this->value as $value) {
				if(preg_match('#\s+#i', $value)) {
					$return .= " \"{$value}\"";
				} else {
					$return .= " {$value}";
				}
			}
			return $return;
		}
	}
