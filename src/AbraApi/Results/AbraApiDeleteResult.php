<?php 

	namespace AbraApi\Results;

	class AbraApiDeleteResult extends AbstractAbraApiResult implements Interfaces\IDeleteResult {

		public function __construct($result, $headers, $httpCode) {
			$this->parseResult($result);
			$this->parseHeaders($headers);			
			$this->setHttpCode($httpCode);
		}

		private function parseResult($result) {
			$this->content = json_decode($result);
		}
	}