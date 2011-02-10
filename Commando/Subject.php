<?php
	/**
	 * Commando_Subject
	 *
	 * This class allows children to inherit traits of PHP's SplSubject pattern. This allows observer classes to be
	 * associated with them and executed to further affect the behavior of a command line utility.
	 *
	 * @package Commando
	 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
	 * @license GNU Lesser General Public License v3 <http://www.gnu.org/copyleft/lesser.html>
	 * @copyright Copyright (c) 2011, Daniel Wilhelm II Murdoch
	 * @since v0.1.0a
	 * @link http://www.thedrunkenepic.com
	 */
	abstract class Commando_Subject implements SplSubject {
		/**
		 * An array containing a collection of observer instances.
		 * @access Private
		 * @var Object
		 */
		private $observers;

		/**
		 * Instantiates class and defines instance variables.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Commando_Subject
		 */
		public function __construct() {
			$this->observers = array();
		}

		/**
		 * Used to associate an object which implements PHP's SplObserver pattern.
		 *
		 * @param Object SplObserver $Observer An instance of an observer.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando_Subject
		 */
		public function attach(SplObserver $Observer) {
			$this->observers[spl_object_hash($Observer)] = $Observer;
			return $this;
		}

		/**
		 * Used to remove an assigned observer object.
		 *
		 * @param Object SplObserver $Observer An instance of an observer to remove.
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando_Subject
		 */
		public function detach(SplObserver $Observer) {
			unset($this->observers[spl_object_hash($Observer)]);
			return $this;
		}

		/**
		 * When invoked, all associated observer objects will be notified and provided the current instance
		 * of any child class.
		 *
		 * @param None
		 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
		 * @access Public
		 * @return Object Commando_Subject
		 */
		public function notify() {
			foreach($this->observers as $Observer) {
				$Observer->update($this);
			}
			return $this;
		}
	}