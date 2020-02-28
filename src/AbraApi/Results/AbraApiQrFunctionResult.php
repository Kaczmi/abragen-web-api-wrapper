<?php 

	namespace AbraApi\Results;

	class AbraApiQrFunctionResult extends AbstractAbraApiResult implements Interfaces\IQrFunctionResult {

		public function __construct($result, $headers, $httpCode) {
			$this->parseResult($result);
			$this->parseHeaders($headers);			
			$this->setHttpCode($httpCode);
		}

		private function parseResult($result) {
			$this->content = json_decode($result);
		}

		public function getResult() {
			return $this->content->result;
		}

	}