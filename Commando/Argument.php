<?php

	class Commando_Argument extends Commando_Subject {
		private $isRequired;
		private $isValueRequired;
		public $title;
		private $value;
		private $valuePattern;
		static public function factory($title, $isRequired = false, $isValueRequired = false, $valuePattern = null) {
			return new self($title, $isRequired, $isValueRequired, $valuePattern);
		}
		public function __construct($title, $isRequired = false, $isValueRequired = false, $valuePattern = null) {
			parent::__construct();
			$this->observers       = array();
			$this->title           = strtolower($title);
			$this->valuePattern    = $valuePattern;
			$this->isRequired      = $isRequired;
			$this->isValueRequired = $isValueRequired;
			$this->value           = null;
		}
		public function isRequired() {
			return (bool) $this->isRequired;
		}
		public function isValueRequired() {
			return (bool) $this->isValueRequired;
		}
		public function setValue($value) {
			if(is_null($this->valuePattern) === false && preg_match("#^{$this->valuePattern}$#i", $value) == false) {
				throw new InvalidArgumentException('Invalid parameter value');
			}
			$this->value = $value;
		}
		public function getValue() {
			return $this->value;
		}
	}