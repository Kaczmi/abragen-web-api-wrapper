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
		public function from(string $fromBussinessObject): ImportQuery {
			$this->fromBussinessObject = $fromBussinessObject;
			return $this;
		}

		/**
		 * Specifies documents ID´s to be imported into target BO
		 */
		public function inputDocuments(array $documents): ImportQuery {
			$this->queryServant->inputDocuments($documents);
			return $this;
		}

		/**
		 * Specifies into what bussiness object are importing
		 */
		public function into(string $intoBussinessObject): ImportQuery {
			$this->intoBussinessObject = $intoBussinessObject;
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
			if($this->fromBussinessObject === null || $this->intoBussinessObject === null)
				throw new \Exception("You must specify ->from(string $bussinessObject) and ->into(string $bussinessObject) to execute import query.");
			return $this->resultGetter->getResult($this->getApiEndpoint(), $this->getQuery());
		}

		/**
		 * Creates endpoint for query
		 */
		public function getApiEndpoint() {
			if(!preg_match('/^(.*?)\/(\d{10})$/m', $this->fromBussinessObject) && !$this->queryServant->hasCommand(Commands\InputDocumentsCommand::class)) {
				throw new \Exception("You must specify document you are importing from via ->from('bo/id') or ->inputDocuments(array $ids)");
			}
			return ($this->intoBussinessObject)
					."/import/".($this->fromBussinessObject)
					."?".(QueryHelpers::createSelectUri($this->queryServant));
		}

		/**
		 * Merges all data commands and return it as JSON object
		 */
		public function getQuery(): string {
			$mergedDataCommands = QueryHelpers::mergeCommands($this->queryServant, [ Commands\ParamsCommand::class,
																					 Commands\InputDocumentsCommand::class,
																					 Commands\OutputDocumentCommand::class ]);
			if(count($mergedDataCommands) === 0) 
				throw new \Exception("You need to specify data() - which columns are supposed to be edited in update query");
			return json_encode($mergedDataCommands);
		}

	}