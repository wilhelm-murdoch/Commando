<?php
	class Commando_Prompt extends Commando_Subject {
		private $Validator;
		private $title;
		private $prompt;
		private $reprompt;
		private $response;
		private $isRequired;
		public function  __construct($title, $prompt, $isRequired = false, $reprompt = "Error: value required!\n") {
			parent::__construct();
			$this->title      = $title;
			$this->prompt     = $prompt;
			$this->reprompt   = $reprompt;
			$this->isRequired = $isRequired;
			$this->Validator  = null;
		}

		static public function factory($title, $prompt, $isRequired = false, $reprompt = "Error: value required!\n") {
			return new self($title, $prompt, $isRequired, $reprompt);
		}
		public function getTitle() {
			return $this->title;
		}
		public function addValidator($Validator) {
			$this->Validator = $Validator;
			return $this;
		}
		public function getResponse() {
			return $this->response;
		}
		public function getReprompt() {
			return $this->reprompt;
		}
		public function show() {
			fwrite(STDOUT, $this->prompt);
			$this->response = trim(fgets(STDIN));

			if(!$this->response && $this->isRequired) {
				fwrite(STDOUT, $this->reprompt);
				return $this->show();
			}

			if(is_null($this->Validator) === false) {
				$this->response = $this->Validator->isValid($this);
			}

			$this->notify();
			return $this;
		}
	}