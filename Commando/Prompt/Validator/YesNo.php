<?php
	class Commando_Prompt_Validator_YesNo extends Commando_Prompt_Validator {
		public function __construct() {
			parent::__construct();
		}

		static public function factory() {
			return new self;
		}
	}
