<?php
	abstract class Commando_Prompt_Validator extends Commando_Subject {
		public $Prompt;
		protected $validDecorator;
		protected $invalidDecorator;

		public function __construct() {
			parent::__construct();
			$this->Prompt           = null;
			$this->validDecorator   = null;
			$this->invalidDecorator = null;
		}

		public function validate(Commando_Prompt $Prompt) {
			$this->Prompt = $Prompt;
			$this->executeDecorator($this->validDecorator);
			return $this->Prompt->getResponse();
		}

		public function ifValid($decorator) {
			$this->validDecorator = $decorator;
			return $this;
		}

		public function ifNotValid($decorator) {
			$this->invalidDecorator = $decorator;
			return $this;
		}

		protected function executeDecorator($decorator) {
			if(is_null($decorator)) {
				return null;
			}
			if($decorator instanceof Commando_Prompt_Validator) {
				$decorator->isValid($this->Prompt);
			} elseif($decorator instanceof Commando_Prompt) {
				$decorator->show();
			} elseif($decorator instanceof Closure) {
				$Closure = new RefectionFunction($decorator);
				$Closure->invokeArgs($this);
			}elseif(is_callable($decorator, true)) {
				call_user_func($decorator, $this);
			} else {
				throw new InvalidArgumentException('Unknown decorator type specified.');
			}
			return $this;
		}
	}
