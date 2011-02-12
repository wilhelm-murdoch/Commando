<?php
	/**
	 * Commando_Argument_Value_Single
	 *
	 * This value type represents a standard single value. It is very similar to Commando_Argument_Value_Multi with the
	 * exception of the fact that you may apply as many value types as you like. This allows you to create multiple and
	 * required argument values, each with their own validation patterns. This provides you with far greater flexibility
	 * in how you set up your various arguments.
	 *
	 * Incoming argument values are assigned to a defined instance of Commando_Argument_Value_Single by a simple array
	 * index one-to-one match. For example, if there are two instances of this class associated with a given argument
	 * and 3 values are passed to it, the following rules will apply:
	 *
	 * $possibleArgumentValues[0] will be assigned to Commando_Argument::$values[0]
	 * $possibleArgumentValues[1] will be assigned to Commando_Argument::$values[1]
	 * $possibleArgumentValues[2] will be ignored as a third instance of class Commando_Argument_Value_Single has
	 * not been associated with the given argument.
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
	class Commando_Argument_Value_Single extends Commando_Argument_Value {
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
			parent::__construct($isRequired, $valuePattern);
		}

		/**
		 * Factory pattern which returns a brand new instance of this class
		 *
		 * @param Boolean $isRequired   Determines whether this is a required value
		 * @param String  $valuePattern Used to validate specified values
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando_Argument_Value
		 * @static
		 * @uses Commando::__construct()
		 */
		static public function factory($isRequired = false, $valuePattern = null) {
			return new self($isRequired, $valuePattern);
		}
	}
