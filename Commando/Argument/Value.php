<?php
	class Commando_Argument_Value extends Commando_Subject {
		private $isRequired;
		private $isMaster;
		private $valuePattern;
		private $value;

		public function __construct($isRequired = false, $isMaster = false, $valuePattern = null) {
			parent::__construct();
			$this->isRequired   = $isRequired;
			$this->isMaster     = $isMaster;
			$this->valuePattern = $valuePattern;
			$this->value        = null;
		}

		static public function factory($isRequired = false, $isMaster = false, $valuePattern = null) {
			return new self($isRequired, $isMaster, $valuePattern);
		}

		public function isRequired() {
			return (bool) $this->isRequired;
		}

		public function hasValue() {
			return (bool) $this->value;
		}

		public function isMaster() {
			return (bool) $this->isMaster;
		}

		public function isValid($value) {
			return (is_null($this->valuePattern) === false ? (bool) preg_match("#^{$this->valuePattern}$#i", $value) : true);
		}

		public function set($value) {
			$this->value = $value;
		}

		public function getValue() {
			return $this->value;
		}

		public function  __toString() {
			return $this->value;
		}
	}