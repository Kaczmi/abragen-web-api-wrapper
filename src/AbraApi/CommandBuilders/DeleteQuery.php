<?php 

	declare(strict_types=1);

	namespace AbraApi\CommandBuilders;	

	use AbraApi\Executors\Interfaces\IExecutor,
		AbraApi\Callers,
		AbraApi\Results\Interfaces\IDeleteResult;

	class DeleteQuery extends Query {

		private Callers\DeleteQueryResultGetter $resultGetter;
		private QueryServant $queryServant;
		private ?string $boId = null;
		private ?string $source = null;

		public function __construct(Callers\DeleteQueryResultGetter $resultGetter) {
			$this->resultGetter = $resultGetter;
			$this->queryServant = new QueryServant;
		}

		/**
		 * When you need to specify source to be deleted and you can´t use default simple usage
		 */
		public function setSource(string $source): DeleteQuery {
			$this->source = $source;
			return $this;
		}

		/**
		 * Defines, what BO are we quering into
		 */
		public function class(string $class): DeleteQuery {
			$this->queryServant->class($class);
			return $this;
		}

		/**
		 * Which rows are supposed to be deleted
		 * AbraAPI does not support deleting multiple rows at once, so we need to only one ID
		 */
		public function whereId(string $id): DeleteQuery {
			$this->boId = $id;
			return $this;
		}

		/**
		 * Executes query and returns deletes data in abra
		 * Query uses DELETE method
		 */
		public function execute(): IDeleteResult {
			return $this->resultGetter->getResult($this->getApiEndpoint(), "", []);
		}

		/**
		 * Get´s endpoint for query
		 */
		public function getApiEndpoint(): string {
			if($this->queryServant->hasCommand(\AbraApi\Commands\ClassCommand::class) && $this->source !== null)
				throw new \Exception("You can set only one source for delete() query.");
			if($this->queryServant->hasCommand(\AbraApi\Commands\ClassCommand::class) && $this->boId === null)
				throw new \Exception("You need to specify ID of row to be deleted using whereId() command");
			if($this->queryServant->hasCommand(\AbraApi\Commands\ClassCommand::class)) {
				return $this->queryServant->getQueryCommand(\AbraApi\Commands\ClassCommand::class)->getClass()."/".$this->boId;
			}
			return $this->source;
		}

	}