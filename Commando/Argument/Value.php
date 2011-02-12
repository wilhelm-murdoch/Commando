<?php
	/**
	 * Commando_Argument_Value
	 *
	 * Base abstract class for all derived sub-class value types. Contains methods and properties which are common
	 * among all value types. All public and protected methods can be overloaded. If you wish to create a custom
	 * value type class, it MUST extend from this class.
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
	abstract class Commando_Argument_Value extends Commando_Subject {
		/**
		 * Determines whether current instance is a required value.
		 * @access Private
		 * @var Boolean
		 */
		private $isRequired;

		/**
		 * A regular expression which can be used to validate a specified value.
		 * @access Private
		 * @var String
		 */
		private $valuePattern;

		/**
		 * The actual value of the current instance of this class.
		 * @access Private
		 * @var Mixed
		 */
		protected $value;

		/**
		 * Instantiates class and defines instance variables.
		 *
		 * @param Boolean $isRequired   Determines whether this is a required value
		 * @param String  $valuePattern Used to validate specified values
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Commando_Argument_Value
		 */
		public function __construct($isRequired = false, $valuePattern = null) {
			parent::__construct();
			$this->isRequired   = $isRequired;
			$this->valuePattern = $valuePattern;
			$this->value        = '';
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
		 * @return Object $this
		 * @uses Commando_Subject::notify()
		 */
		public function setValue($value) {
			$this->value = $value;
			$this->notify($this);
			return $this;
		}

		/**
		 * Returns the value assigned to the current instance of this class.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Mixed
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