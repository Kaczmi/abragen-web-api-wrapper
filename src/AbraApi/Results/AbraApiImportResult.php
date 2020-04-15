<?php 

	namespace AbraApi\Results;

	class AbraApiImportResult extends AbstractAbraApiResult implements Interfaces\IImportResult {

		public function __construct($result, $headers, $httpCode) {
			$this->parseResult($result);
			$this->parseHeaders($headers);			
			$this->setHttpCode($httpCode);
		}

		private function parseResult($result) {
			$this->content = json_decode($result);
		}

		public function getId(): string {
			return $this->content->id;
		}

		public function getResult() {
			return $this->content;
		}

	}