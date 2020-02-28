<?php 

	declare(strict_types=1);

	namespace AbraApi\CommandBuilders;	

	use AbraApi\Executors\Interfaces\IExecutor,
		AbraApi\Callers,
		AbraApi\Results\Interfaces\IDataResult,
		AbraApi\Commands\ExpandCommand;

	class GetQuery extends Query implements Interfaces\IExpandQuery {

		private $resultGetter;
		private $queryServant;

		public function __construct(IExecutor $executor, Callers\Interfaces\IResultGetter $resultGetter) {
			$this->setExecutor($executor);
			$this->resultGetter = $resultGetter;
			$this->queryServant = new QueryServant;
		}

		/**
		 * Defines, what BO are we quering into
		 */
		public function class($class): GetQuery {
			$this->queryServant->class($class);
			return $this;
		}

		/**
		 * What columns must query return
		 */
		public function select(...$selects): GetQuery {
			$this->queryServant->select(...$selects);
			return $this;
		}

		/**
		 * Condition
		 */
		public function where($query, ...$parameters): GetQuery {
			$this->queryServant->where($query, ...$parameters);
			return $this;
		}

		/**
		 * Condition, specific for ID
		 */
		public function whereId($ids): GetQuery {
			$this->queryServant->whereId($ids);
			return $this;
		}

		/**
		 * Creates subselect
		 */
		public function expand($name, $value = null): ExpandCommand {
			return $this->queryServant->expand($name, $value, $this->executor, $this);
		}

		/**
		 * Limit of rows to be selected
		 */
		public function limit($limit): GetQuery {
			$this->queryServant->limit($limit);
			return $this;
		}

		/**
		 * Amount of skipped rows
		 */
		public function skip($skip): GetQuery {
			$this->queryServant->skip($skip);
			return $this;
		}

		/**
		 * Creates subselect
		 */
		public function orderBy(...$orderBy): GetQuery {
			$this->queryServant->orderBy(...$orderBy);
			return $this;
		}

		/**
		 * Creates groupby aggregation
		 */
		public function groupBy(...$groupBy): GetQuery {
			$this->queryServant->groupBy(...$groupBy);
			return $this;
		}

		/**
		 * Executes query and returns data result
		 */
		public function execute(): IDataResult {
			return $this->resultGetter->getResult("query", $this->getQuery());
		}

		/**
		 * Returns query as a string to be send to AbraApiWorker
		 */
		public function getQuery(): string {
			$queryBody = $this->executor->execute($this->queryServant);
			return json_encode($queryBody);
		}

		/**
		 * Fetches first row returned by Abra
		 */
		public function fetch() {
			return $this->execute()->fetch();
		}

		/**
		 * Fetches specific field in first row returned by Abra
		 */
		public function fetchField($field) {
			return $this->execute()->fetchField($field);
		}

		/**
		 * Fetches all rows returned by Abra as JSON object
		 */
		public function fetchAll(): array {
			return $this->execute()->fetchAll();
		}

		/**
		 * Returns specific field ($field) as flat array ([1, 2, 3, ...])
		 * E.g. you want to get all ID´s of invoices for specific firm, and you want it in 1-dimensional array
		 * Result could be this for example: [ { id: 1 }, { id: 2 }, { id: 3} ]
		 * You use $command->select....->fetchFlat("id") and you get [1, 2, 3]
		 */
		public function fetchFlat($field): array {
			return $this->execute()->fetchFlat($field);
		} 

	}