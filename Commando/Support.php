<?php
	/**
	 * Interface Commando_Support
	 *
	 * If a class needs to be wrapped by an instance of Commando, it should implement the following interface
	 * for Commando support. This ensures a common method of execution for wrapped objects. Implementing this
	 * interface is definitely not required, but may make things a bit more convenienct.
	 *
	 * @package Commando
	 * @author Daniel Wilhelm II Murdoch <wilhelm.murdoch@gmail.com>
	 * @license GNU Lesser General Public License v3 <http://www.gnu.org/copyleft/lesser.html>
	 * @copyright Copyright (c) 2011, Daniel Wilhelm II Murdoch
	 * @since v0.1.0a
	 * @link http://www.thedrunkenepic.com
	 */
	interface Commando_Support {
		static public function commando(Commando $Commando);
	}