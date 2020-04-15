<?php 

	declare(strict_types=1);

	namespace AbraApi\CommandBuilders;	

	use AbraApi\Executors\Interfaces\IExecutor,
		AbraApi\Callers,
		AbraApi\Results\Interfaces\IUpdateResult,
		AbraApi\Commands;

	class ImportQuery extends Query {
		/** @var Callers\Interfaces\IResultGetter */
		private $resultGetter;
		/** @var QueryServant */
		private $queryServant;
		/** @var string|null */
		private $fromBussinessObject;
		/** @var string|null */
		private $intoBussinessObject;
		/** @var string|null */
		private $intoDocumentId;

		public function __construct(Callers\Interfaces\IResultGetter $resultGetter) {
			$this->resultGetter = $resultGetter;
			$this->queryServant = new QueryServant;
		}

		/**
		 * What columns should result return
		 * If this function is not specified whilst updating data, system automatically selects only ID of updated row
		 */
		public function select(...$selects): ImportQuery {
			$this->queryServant->select(...$selects);
			return $this;
		}

		/**
		 * Specifies from what bussiness object are we importing
		 */
		public function from(string $fromBussinessObject, array $documentIds): ImportQuery {
			$this->fromBussinessObject = $fromBussinessObject;
			$this->inputDocuments($documentIds);
			return $this;
		}

		/**
		 * Specifies documents ID´s to be imported into target BO
		 */
		private function inputDocuments(array $documents): ImportQuery {
			$this->queryServant->inputDocuments($documents);
			return $this;
		}

		/**
		 * Specifies into what bussiness object are importing
		 * In second parameter, you can specify ID of row to be imported into
		 */
		public function into(string $intoBussinessObject, string $intoDocumentId = null): ImportQuery {
			$this->intoBussinessObject = $intoBussinessObject;
			if($intoDocumentId !== null && strlen($intoDocumentId) !== 10)
				throw new \Exception("ID of document was expected, '".$intoDocumentId."' given (required in length of 10 characters)");
			$this->intoDocumentId = $intoDocumentId;
			return $this;
		}

		/**
		 * Specifies data to be set when new imported document is saved
		 */
		public function outputDocumentData(...$data): ImportQuery {
			$this->queryServant->outputDocumentData(...$data);
			return $this;
		}

		/**
		 * Specify import parameters (docqueue_id, ...)
		 */
		public function params(...$data): ImportQuery {
			$this->queryServant->params(...$data);
			return $this;
		}

		/**
		 * Executes query and returns result of imported document
		 * If you don´t specify ->select(..), it returns only ID
		 */
		public function execute(): IUpdateResult {
			
			return $this->resultGetter->getResult($this->getApiEndpoint(), $this->getQuery());
		}

		/**
		 * Creates endpoint for query
		 */
		public function getApiEndpoint() {
			return "import?".(QueryHelpers::createSelectUri($this->queryServant));
		}

		/**
		 * Merges all data commands and return it as JSON object
		 */
		public function getQuery(): string {
			if($this->fromBussinessObject === null || $this->intoBussinessObject === null)
				throw new \Exception('You must specify ->from(string $bussinessObject, array $documentIds) and ->into(string $bussinessObject, ?string $documentId) to execute import query.');
			$query = [
				"input_document_clsid" => $this->fromBussinessObject,
				"output_document_clsid" => $this->intoBussinessObject
			];
			if($this->intoDocumentId !== null)
				$query["output_document"] = $this->intoDocumentId;
			$mergedCommands = QueryHelpers::mergeCommands($this->queryServant, [ Commands\ParamsCommand::class,
																				 Commands\InputDocumentsCommand::class,
																				 Commands\OutputDocumentCommand::class ]);
			
			return json_encode(array_merge($mergedCommands, $query));
		}

	}