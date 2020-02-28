<?php 

	namespace AbraApi\Results;

	class AbraApiUpdateResult extends AbstractAbraApiResult implements Interfaces\IUpdateResult {

		public function __construct($result, $headers, $httpCode) {
			$this->parseResult($result);
			$this->parseHeaders($headers);			
			$this->setHttpCode($httpCode);
		}

		private function parseResult($result) {
			$this->content = json_decode($result);
		}

		public function getUpdatedId() {
			return $this->content->id;
		}

		public function getResult() {
			return $this->content;
		}

	}