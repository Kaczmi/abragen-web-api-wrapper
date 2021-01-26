<?php 

	declare(strict_types=1);

	namespace AbraApi\CommandBuilders;	

	use AbraApi\Executors\Interfaces\IExecutor,
		AbraApi\Callers,
		AbraApi\Results\Interfaces\IImportResult,
		AbraApi\Commands;

	class ImportQuery extends Query {
		private Callers\ImportQueryResultGetter $resultGetter;
		private QueryServant $queryServant;
		private ?string $fromBussinessObject = NULL;
		private ?string $intoBussinessObject = NULL;
		private ?string $intoDocumentId = NULL;

		public function __construct(Callers\ImportQueryResultGetter $resultGetter) {
			$this->resultGetter = $resultGetter;
			$this->queryServant = new QueryServant;
		}

		/**
		 * What columns should result return
		 * If this function is not specified whilst updating data, system automatically selects only ID of updated row
		 * @param string|array<string>|array<string, string>|array<int, string> ...$selects
		 */
		public function select(...$selects): ImportQuery {
			$this->queryServant->select(...$selects);
			return $this;
		}

		/**
		 * Specifies from what bussiness object are we importing
		 * @param array<string> $documentIds
		 */
		public function from(string $fromBussinessObject, array $documentIds): ImportQuery {
			$this->fromBussinessObject = $fromBussinessObject;
			$this->inputDocuments($documentIds);
			return $this;
		}

		/**
		 * Specifies documents ID´s to be imported into target BO
		 * @param array<string> $documents
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
		 * @param array<string, string>|string ...$data
		 */
		public function outputDocumentData(...$data): ImportQuery {
			$this->queryServant->outputDocumentData(...$data);
			return $this;
		}

		/**
		 * Specify import parameters (docqueue_id, ...)
		 * @param array<string, string>|string ...$data
		 */
		public function params(...$data): ImportQuery {
			$this->queryServant->params(...$data);
			return $this;
		}

		/**
		 * Executes query and returns result of imported document
		 * If you don´t specify ->select(..), it returns only ID
		 */
		public function execute(): IImportResult {
			if($this->fromBussinessObject === null || $this->intoBussinessObject === null)
				throw new \Exception('You must specify ->from(string $bussinessObject, array $documentIds) and ->into(string $bussinessObject, ?string $documentId) to execute import query.');
			if(($this->isClsid($this->fromBussinessObject) ^ $this->isClsid($this->intoBussinessObject)))
				throw new \Exception("You must specify both left and right importing manager bussiness objects either by ClsID or by API Bussiness object name.");
			$resultGetter = $this->resultGetter;
			if($this->intoDocumentId !== null && !$this->isClsid($this->fromBussinessObject)) {
				$resultGetter->usePutMethod();
			}
			return $this->resultGetter->getResult($this->getApiEndpoint(), $this->getQuery());
		}

		/**
		 * Creates endpoint for query
		 */
		public function getApiEndpoint(): string {
			if($this->isClsid($this->fromBussinessObject)) // both BO´s are defined with CLSID
				return "import?".(QueryHelpers::createSelectUri($this->queryServant));
			return ($this->intoBussinessObject).
				   "/import/".
				   ($this->fromBussinessObject).
				   ("?".(QueryHelpers::createSelectUri($this->queryServant)));
		}

		/**
		 * Merges all data commands and return it as JSON object
		 */
		public function getQuery(): string {
			$query = [];
			if($this->isClsid($this->fromBussinessObject) && $this->isClsid($this->intoBussinessObject)) {
				$query = [
					"input_document_clsid" => $this->fromBussinessObject,
					"output_document_clsid" => $this->intoBussinessObject
				];
			}
			if($this->intoDocumentId !== null)
				$query["output_document"] = $this->intoDocumentId;
			$mergedCommands = QueryHelpers::mergeCommands($this->queryServant, [ Commands\ParamsCommand::class,
																				 Commands\InputDocumentsCommand::class,
																				 Commands\OutputDocumentCommand::class ]);

			$query = \json_encode(\array_merge($mergedCommands, $query));
			if($query === FALSE) {
				throw new \Exception("Could not create query");
			}

			return $query;
		}

		/**
		 * Returns, if string in parameter is CLSID
		 */
		private function isClsid(string $clsid): bool {
			return (bool) (preg_match('/^[0-9A-Z]{26}$/', $clsid));
		}

	}