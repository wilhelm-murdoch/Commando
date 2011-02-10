<?php

	class Commando_Subject implements SplSubject {
		private $observers;
		public function __construct() {}
		public function attach(SplObserver $Observer) {
			$this->observers[spl_object_hash($Observer)] = $Observer;
			return $this;
		}
		public function detach(SplObserver $Observer) {
			unset($this->observers[spl_object_hash($Observer)]);
			return $this;
		}
		public function notify() {
			foreach($this->observers as $Observer) {
				$Observer->update($this);
			}
			return $this;
		}
	}