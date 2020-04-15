<?php 

	namespace AbraApi\Commands;

	class InputDocumentsCommand implements Interfaces\ICommandQueryBuilder {

		const DOCUMENTS_SELECTOR = "input_documents";
		/** @var array */
		private $inputDocuments = [];

		public function __construct(array $inputDocuments) {
			$this->validateInputDocuments($inputDocuments);
			$this->inputDocuments = $inputDocuments;
		}

		private function validateInputDocuments(array $inputDocuments) {
			foreach($inputDocuments as $documentId) {
				if(!is_string($documentId) || strlen($documentId) !== 10) {
					throw new \Exception("Documents are supposed to be array of Bussiness object ID´s (string with length of 10 characters)");
				}
			}
		}

		public function getCommand(): array {
			if(count($this->inputDocuments) === 1)
				return [ self::DOCUMENTS_SELECTOR => $this->inputDocuments[0] ];
			return [ self::DOCUMENTS_SELECTOR => $this->inputDocuments ];
		}

	}