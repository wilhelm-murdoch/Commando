<?php
	abstract class Commando_Prompt_Validator extends Commando_Subject {
		public function __construct() {
			parent::__construct();
		}
		public function isValid(Commando_Prompt $Prompt) {
			return true;
		}
	}
