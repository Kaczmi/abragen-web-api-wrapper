<?php 

	namespace AbraApi\Commands;

	use AbraApi\Executors\Interfaces\IExecutor,
		AbraApi\CommandBuilders\Interfaces\IExpandQuery,
		AbraApi\CommandBuilders\QueryServant,
		AbraApi\Commands\ClassCommand;

	class ExpandCommand implements Interfaces\ICommandQueryBuilder, IExpandQuery {
		/** @var IExpandQuery */
		private $parentQuery;
		/** @var string */
		private $name;
		/** @var string */
		private $value;
		/** @var QueryServant */
		private $queryServant;
		/** @var IExecutor */
		private $executor;

		/**
		 * Every command which support expand command must implement IExpandQuery to support submerged queries
		 */
		public function __construct(IExpandQuery $parentQuery, string $name, string $value, IExecutor $executor) {
			$this->parentQuery = $parentQuery;
			$this->name = $name;
			$this->value = $value;
			$this->executor = $executor;
			$this->queryServant = new QueryServant;
		}

		/**
		 * Defines, what BO are we quering into
		 */
		public function class($class): ExpandCommand {
			$this->queryServant->class($class);
			return $this;
		}

		/**
		 * What columns must query return
		 */
		public function select(...$selects): ExpandCommand {
			$this->queryServant->select(...$selects);
			return $this;
		}

		/**
		 * Condition for expanded command
		 */
		public function where($query, ...$parameters): ExpandCommand {
			$this->queryServant->where($query, ...$parameters);
			return $this;
		}

		/**
		 * Limit of selected rows
		 */
		public function limit($limit): ExpandCommand {
			$this->queryServant->limit($limit);
			return $this;
		}

		/**
		 * Amount of skipped rows
		 */
		public function skip($skip): ExpandCommand {
			$this->queryServant->skip($skip);
			return $this;
		}

		/**
		 * Orders selected rows by specified data structure
		 */
		public function orderBy(...$orderBy): ExpandCommand {
			$this->queryServant->orderBy(...$orderBy);
			return $this;
		}

		/**
		 * Creates subselect
		 */
		public function expand($name, $value = null): ExpandCommand {
			return $this->queryServant->expand($name, $value, $this->executor, $this);
		}

		/**
		 * Creates groupby aggregation
		 */
		public function groupBy(...$groupBy): ExpandCommand {
			$this->queryServant->groupBy(...$groupBy);
			return $this;
		}

		/**
		 * Ends expand query and jumps on to parent query
		 */
		public function end(): IExpandQuery {
			return $this->parentQuery;
		}

		public function getCommand() {
			$expandCommand = [];
			$expandCommand["name"] = $this->name;
			$query = $this->execute();
			if($this->queryServant->hasCommand(\AbraApi\Commands\ClassCommand::class)) {
				$expandCommand["value"] = $query;
			}
			else {
				$expandCommand["value"]["field"] = $this->value;
				$expandCommand["value"]["query"] = $query;
			}
			return $expandCommand;
		}

		private function execute() {
			return $this->executor->execute($this->queryServant);
		}

	}