<?php 

	declare(strict_types=1);

	namespace AbraApi\CommandBuilders;	

	use AbraApi\Executors\Interfaces\IExecutor,
		AbraApi\Callers,
		AbraApi\Results\Interfaces\IUpdateResult,
		AbraApi\Commands;

	class UpdateQuery extends Query {

		private $resultGetter;
		private $queryServant;
		private $updateRowId;

		public function __construct(Callers\Interfaces\IResultGetter $resultGetter) {
			$this->resultGetter = $resultGetter;
			$this->queryServant = new QueryServant;
		}

		/**
		 * Defines, what BO are we quering into
		 */
		public function class($class): UpdateQuery {
			$this->queryServant->class($class);
			return $this;
		}

		/**
		 * What columns should result return
		 * If this function is not specified whilst updating data, system automatically selects only ID of updated row
		 */
		public function select(...$selects): UpdateQuery {
			$this->queryServant->select(...$selects);
			return $this;
		}

		/**
		 * What columns are supposed to be updated
		 */
		public function data(...$data): UpdateQuery {
			$this->queryServant->data(...$data);
			return $this;
		}

		/**
		 * What columns must query return
		 */
		public function whereId(string $id): UpdateQuery {
			$this->updateRowId = $id;
			return $this;
		}

		/**
		 * Executes query and returns update data result
		 * Query uses PUT method
		 */
		public function execute(): IUpdateResult {
			return $this->resultGetter->getResult($this->getApiEndpoint(), $this->getQuery());
		}

		/**
		 * Creates endpoint for query
		 */
		public function getApiEndpoint() {
			if(!$this->queryServant->hasCommand(Commands\ClassCommand::class)) 
				throw new \Exception("Update query must specify bussiness object to be edited (class)");
			if($this->updateRowId === null) 
				throw new \Exception("Update query must specify ID of row to be edited.");
			return ($this->queryServant->getQueryCommand(Commands\ClassCommand::class)->getClass())
					."/".($this->updateRowId)
					."?".(QueryHelpers::createSelectUri($this->queryServant));
		}

		/**
		 * Merges all data commands and return it as JSON object
		 */
		public function getQuery(): string {
			$mergedDataCommands = QueryHelpers::mergeDataCommands($this->queryServant);
			if(count($mergedDataCommands) === 0) 
				throw new \Exception("You need to specify data() - which columns are supposed to be edited in update query");
			return json_encode($mergedDataCommands);
		}

	}