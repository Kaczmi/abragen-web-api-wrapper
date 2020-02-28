<?php 

	namespace AbraApi\Results;

	class AbraApiInsertResult extends AbstractAbraApiResult implements Interfaces\IInsertResult {

		public function __construct($result, $headers, $httpCode) {
			$this->parseResult($result);
			$this->parseHeaders($headers);			
			$this->setHttpCode($httpCode);
		}

		private function parseResult($result) {
			$this->content = json_decode($result);
		}

		public function getInsertedId(): string {
			return $this->content->id;
		}

		public function getResult() {
			return $this->content;
		}

	}