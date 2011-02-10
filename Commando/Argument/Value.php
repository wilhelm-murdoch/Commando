<?php
	/**
	 * Commando_Argument_Value
	 *
	 * Represents an expected value of an associated Commando_Argument instance. Values directly affect the behavior of arguments
	 * by defining requirements such as:
	 *
	 * 1. How many are expected
	 * 2. How many are required
	 * 3. What pattern restrictions, if any, needs to be enforced
	 *
	 * This class also extends Commando_Subject, which implements PHP's SplSubject pattern. This means you can attach observers to
	 * a value to even further affect your utility's behavior. All assigned observers are invoked once a value has been assigned to
	 * a Commmando_Argument_Value instance from within Commando_Argument::bindPossibleArgumentValues().
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
	class Commando_Argument_Value extends Commando_Subject {
		/**
		 * Determines whether current instance is a required value.
		 * @access Private
		 * @var Boolean
		 */
		private $isRequired;

		/**
		 * Determines whether this a master argument value. An argument may only have one master value. If a master
		 * value is assigned, no other values are considered.
		 * @access Private
		 * @var Boolean
		 */
		private $isMaster;

		/**
		 * A regular expression which can be used to validate a specified value.
		 * @access Private
		 * @var String
		 */
		private $valuePattern;

		/**
		 * The actual value of the current instance of this class.
		 * @access Private
		 * @var String
		 */
		private $value;

		/**
		 * Instantiates class and defines instance variables.
		 *
		 * @param Boolean $isRequired   Determines whether this is a required value
		 * @param Boolean $isMaster     Determines whether this is a master value
		 * @param String  $valuePattern Used to validate specified values
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Commando_Argument_Value
		 */
		public function __construct($isRequired = false, $isMaster = false, $valuePattern = null) {
			parent::__construct();
			$this->isRequired   = $isRequired;
			$this->isMaster     = $isMaster;
			$this->valuePattern = $valuePattern;
			$this->value        = null;
		}

		/**
		 * Factory pattern which returns a brand new instance of this class
		 *
		 * @param Boolean $isRequired   Determines whether this is a required value
		 * @param Boolean $isMaster     Determines whether this is a master value
		 * @param String  $valuePattern Used to validate specified values
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando_Argument_Value
		 * @static
		 * @uses Commando::__construct()
		 */
		static public function factory($isRequired = false, $isMaster = false, $valuePattern = null) {
			return new self($isRequired, $isMaster, $valuePattern);
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
		 * Returns TRUE of the current instance of this class has an assigned value; FALSE if it doesn't.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Boolean
		 */
		public function hasValue() {
			return (bool) $this->value;
		}

		/**
		 * Returns TRUE of the current instance of this class is a master value; FALSE if it isn't.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Boolean
		 */
		public function isMaster() {
			return (bool) $this->isMaster;
		}

		/**
		 * If a value has been assigned to $this->valuePattern, this method will use it to validate the current
		 * instance's assigned value. Returns TRUE if one hasn't been assigned or FALSE if it didn't pass the
		 * pattern validation.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Boolean
		 */
		public function isValid($value) {
			return (is_null($this->valuePattern) === false ? (bool) preg_match("#^{$this->valuePattern}$#i", $value) : true);
		}

		/**
		 * Assigns a value to class property $this->value. Will also notify all attached observer objects.
		 *
		 * @param String $value The value to assign to current instance of this class.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Null
		 */
		public function setValue($value) {
			$this->value = $value;
			$this->notify($this);
		}

		/**
		 * Returns the value assigned to the current instance of this class.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return String
		 */
		public function getValue() {
			return $this->value;
		}

		/**
		 * Magic method which returns the current value assigned to an instance of this class when it is accessed as a string.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return String
		 */
		public function  __toString() {
			return $this->value;
		}
	}