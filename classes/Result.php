<?php

	class Result {	
		const PASSED = 'passed';
		const FAILED = 'failed';
		
		private $result;
		private $message;		
		
		public function __construct($result, $message) {
			$this->result = $result;
			$this->message = $message;
		}
									
		public function setResult($result) {
			$this->result = $result;
		}
		
		public function getResult() {
			return $this->result;
		}

		public function setMessage($message) {
			$this->message = $message;
		}

		public function getMessage() {
			return $this->message;
		}
	}
?>
