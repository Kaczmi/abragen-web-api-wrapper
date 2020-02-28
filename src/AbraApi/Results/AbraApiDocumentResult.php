<?php 

	namespace AbraApi\Results;

	class AbraApiDocumentResult extends AbstractAbraApiResult implements Interfaces\IDocumentResult {

		public function __construct($result, $headers, $httpCode) {
			$this->parseResult($result);
			$this->parseHeaders($headers);			
			$this->setHttpCode($httpCode);
		}

		private function parseResult($result) {
			$this->content = $result;
		}

		public function getContent() {
			return $this->content;
		}

	}