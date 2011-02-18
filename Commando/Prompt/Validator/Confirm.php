<?php
	class Commando_Prompt_Validator_Confirm extends Commando_Prompt_Validator {
		private $message;
		private $error;
		public function __construct($message = 'Please Confirm: ', $error = "No match found!\n") {
			parent::__construct();
			$this->message = $message;
			$this->error   = $error;
		}

		static public function factory($message = 'Please Confirm: ', $error = "No match found!\n") {
			return new self($message, $error);
		}

		public function validate(Commando_Prompt $Prompt) {
			$this->Prompt = $Prompt;

			$Confirm = Commando_Prompt::factory(null, $this->message, true)->show();

			if($Confirm->getResponse() != $Prompt->getResponse()) {
				fwrite(STDOUT, $this->error);
				$this->executeDecorator($this->invalidDecorator);
				return $this->validate($Prompt);
			}

			$this->executeDecorator($this->validDecorator);

			return $this->Prompt->getResponse();
		}
	}
