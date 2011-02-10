<?php

	class Commando_Argument extends Commando_Subject {
		private $isRequired;
		private $title;
		private $description;
		private $values;

		public function __construct($title, $description, $isRequired = false) {
			parent::__construct();
			$this->title       = strtolower($title);
			$this->description = $description;
			$this->isRequired  = $isRequired;
			$this->values      = array();
		}

		static public function factory($title, $description, $isRequired = false) {
			return new self($title, $description, $isRequired);
		}

		public function getTitle($asSwitch = false) {
			return $asSwitch ? Commando::config('argument.switch.plain').$this->title : $this->title;
		}

		public function isRequired() {
			return (bool) $this->isRequired;
		}

		static public function stripSwitch($argumentTitle) {
			return strtolower(preg_replace('#^'.Commando::config('argument.pattern.switch').'#i', '', $argumentTitle));
		}

		static public function isValid($argumentTitle) {
			return (bool) preg_match('#^'.Commando::config('argument.pattern.switch').Commando::config('argument.pattern.title').'$#i', $argumentTitle);
		}

		public function bindPossibleArgumentValues(array $possibleArgumentValues) {
			foreach($possibleArgumentValues as $index => $possibleValue) {
				if(isset($this->values[$index])) {
					echo $possibleValue;
					$this->values[$index]->set($possibleValue);
				}
			}
			return $this;
		}

		public function validate() {
			$invalidValues = array();
			foreach($this->values as $Value) {
				if($Value->isRequired() && $Value->hasValue() === false) {
					throw new InvalidArgumentException('Invalid argument for '.$this->getTitle(true).': '.$Value->getValue());
				}
			}
			return $invalidValues;
		}

		private function hasMasterValue() {
			foreach($this->values as $Value) {
				if($Value->isMaster()) {
					return true;
				}
			}
			return false;
		}

		public function addValue($values) {
			if(is_array($values)) {
				foreach($values as $Value) {
					if($Value instanceof Commando_Argument_Value) {
						if(count($this->values) == 1 && $this->hasMasterValue()) {
							throw new InvalidArgumentException('Argument '.$this->getTitle(true).' already has a master value assigned.');
						}
						$this->values[] = $Value;
					}
				}
			} else {
				if(count($this->values) == 1 && $this->hasMasterValue()) {
					throw new InvalidArgumentException('Argument '.$this->getTitle(true).' already has a master value assigned.');
				}
				if($values instanceof Commando_Argument_Value) {
					$this->values[] = $values;
				}
			}
			return $this;
		}

		public function  __toString() {
			return $this->getTitle(true);
		}
	}