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
					throw new \Exception("Input documents is supposed to be array of Bussiness object ID´s");
				}
			}
		}

		public function getCommand(): array {
			return [ self::DOCUMENTS_SELECTOR => $this->data ];
		}

	}