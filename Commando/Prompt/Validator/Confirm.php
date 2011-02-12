<?php
	class Commando_Prompt_Validator_Confirm extends Commando_Prompt_Validator {
		public function __construct() {
			parent::__construct();
		}

		static public function factory() {
			return new self;
		}

		public function isValid(Commando_Prompt $Prompt) {
			$Confirm = Commando_Prompt::factory(null, 'Please Confirm: ', true)->show();
			if($Confirm->getResponse() != $Prompt->getResponse()) {
				fwrite(STDOUT, $Prompt->getReprompt());
				return $this->isValid($Prompt);
			}
			return $Prompt->getResponse();
		}
	}
