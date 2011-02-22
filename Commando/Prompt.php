<?php
	class Commando_Prompt extends Commando_Subject {
 		private $Validator;
		private $id;
		private $message;
		private $isRequired;
		private $error;
		private $response;
		
		public function  __construct($id, $message, $isRequired = false, $error = "Error: value required!\n") {
			parent::__construct();
			$this->Validator  = null;
			$this->id         = $id;
			$this->message    = $message;
			$this->isRequired = $isRequired;
			$this->error      = $error;
			$this->response   = '';
		}

		static public function factory($id, $message, $isRequired = false, $error = "Error: value required!\n") {
			return new self($id, $message, $isRequired, $error);
		}

		public function getId() {
			return $this->id;
		}

		public function addValidator(Commando_Prompt_Validator $Validator) {
			$this->Validator = $Validator;
			return $this;
		}

		public function setResponse($response) {
			$this->response = $response;
			return $this;
		}

		public function getResponse() {
			return $this->response;
		}

		public function getError() {
			return $this->error;
		}
		
		public function setMessage($message) {
			if(!is_string($message))
				throw new Exception('Prompt must be a string.');
				
			$this->message = $message;
		}

		public function show() {
			fwrite(STDOUT, $this->message);
			$this->response = trim(fgets(STDIN));

			if(!$this->response && $this->isRequired) {
				fwrite(STDOUT, $this->error);
				return $this->show();
			}

			if(is_null($this->Validator) === false) {
				$this->response = $this->Validator->validate($this);
			}

			$this->notify();

			return $this;
		}

		public function  __toString() {
			return $this->getResponse();
		}
	}