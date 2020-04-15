<?php 

	namespace AbraApi\CommandBuilders;

	use AbraApi\Executors\Interfaces\IExecutor,
		AbraApi\Commands,
		AbraApi\Commands\Interfaces\ICommandQueryBuilder,
		AbraApi\Commands\Interfaces\IMultipleCommand,
		AbraApi\Commands\DataCommand,
		AbraApi\Commands\ExpandCommand;

	/**
	 * For creating queries, here is a default service, which implements all methods
	 * needed for all api requests.
	 * E.g. all requests needs to specify Bussiness object, but there is a very specific 
	 * command like report() only needed for fetching documents.
	 * Traits are not good for this solution, so I decided to use Servant design pattern
	 * This object is then passed to Executor to create command (interface IExecutor)
	 */
	class QueryServant {

		protected $query;

		private $queryProperOrder = [
			Commands\ClassCommand::class,
			Commands\FulltextCommand::class,
			Commands\ExprCommand::class,
			Commands\SelectCommand::class,
			Commands\ExpandCommand::class,
			Commands\DataCommand::class,
			Commands\WhereCommand::class,
			Commands\OrderByCommand::class,
			Commands\GroupByCommand::class,
			Commands\LimitCommand::class,
			Commands\SkipCommand::class,
			Commands\ParamsCommand::class
		];

		/**
		 * Adds a query
		 */
		protected function addQuery(ICommandQueryBuilder $newQuery) {
			if($this->query !== null && !($newQuery instanceof IMultipleCommand)) {
				foreach($this->query as $query) {
					if($query instanceof $newQuery) throw new \Exception("Cannot add two same queries of ".get_class($query));
				}
			}
			$this->query[] = $newQuery;
		}

		/**
		 * Gets whole query
		 */
		public function getQuery() {
			$properOrderedQuery = [];
			$actualQuery = $this->query;
			foreach($this->queryProperOrder as $command) {
				foreach($actualQuery as $key => $query) {
					if($query instanceof $command) {
						$properOrderedQuery[] = $query;
						unset($actualQuery[$key]);
					}
				}
			}
			if(count($actualQuery) > 0) {
				array_merge($properOrderedQuery, $actualQuery);
			}
			return $properOrderedQuery;
		}

		/**
		 * Return true if a query has specific command (instance of ..)
		 */
		public function hasCommand($command) {
			if(!is_array($this->query)) return false;
			foreach($this->query as $query) {
				if($query instanceof $command) return true;
			}
			return false;
		}

		/**
		 * Returns specific command from query, if it is not there, returns null
		 */
		public function getQueryCommand($command) {
			if(!is_array($this->query)) return false;
			foreach($this->query as $query) {
				if($query instanceof $command) return $query;
			}
			return null;
		}

		/**
		 * Adds class command to query
		 */
		public function class($className) {
			$this->addQuery(new Commands\ClassCommand($className));
			return $this;
		}

		/**
		 * Adds select command to query, parameters are optional
		 */
		public function select(...$selects) {
			$this->addQuery(new Commands\SelectCommand($selects));
			return $this;
		}

		/**
		 * Adds condition to query, uses question mark for prepared, escaped statements
		 */
		public function where($query, ...$parameters) {
			$this->addQuery(new Commands\WhereCommand($query, ...$parameters));
			return $this;
		}

		/**
		 * Adds condition for ID of row, accepts only one parameter, can be array
		 */
		public function whereId($ids) {
			// id is supposed to be 10 characters long
			if(!is_array($ids)) $ids = [ $ids ];
			foreach($ids as $id) {
				if(strlen($id) !== 10) throw new \Exception("ID of row expected, ".(($id === null) ? "NULL" : ("'".$id."'"))." given.");
			}
			return $this->where("id in (?)", $ids);
		}

		/**
		 * Adds expression query
		 */
		public function expr($expression, ...$parameters) {
			$this->addQuery(new Commands\ExprCommand($expression, ...$parameters));
			return $this;
		}

		/**
		 * Expands query (creates subselect)
		 * @param  string $name  it is defined as name of subselect in returned data structure
		 * @param  string $value if name is defined and expand is not subselect, this defines value of expanded field
		 */
		public function expand($name, $value = null, IExecutor $executor, Interfaces\IExpandQuery $parent): Commands\ExpandCommand {
			$expandCommand = null;
			if($value === null) 
				$expandCommand = (new Commands\ExpandCommand($parent, $name, $name, $executor));
			else
				$expandCommand = (new Commands\ExpandCommand($parent, $name, $value, $executor));
			$this->addQuery($expandCommand);
			return $expandCommand;
		}

		/**
		 * Limit of selected rows
		 */
		public function limit($limit) {
			$this->addQuery(new Commands\LimitCommand($limit));
			return $this;
		}

		/**
		 * Specific amount of rows to be skipped (for pagination for example)
		 */
		public function skip($skip) {
			$this->addQuery(new Commands\SkipCommand($skip));
			return $this;
		}

		/**
		 * Orders selected rows by specified data structure
		 */
		public function orderBy(...$orderBy) {
			$this->addQuery(new Commands\OrderByCommand(...$orderBy));
			return $this;
		}

		/**
		 * Group by command
		 */
		public function groupBy(...$groupBy) {
			$this->addQuery(new Commands\GroupByCommand(...$groupBy));
			return $this;
		}

		/**
		 * Defines, what data are supposed to be updated/inserted
		 */
		public function data(...$data) {
			$this->addQuery(new Commands\DataCommand(...$data));
			return $this;
		}

		/**
		 * Specifies fulltext search
		 */
		public function fulltext(string $fulltext) {
			$this->addQuery(new Commands\FulltextCommand($fulltext));
			return $this;
		}

		/**
		 * Specifies params (import query)
		 */
		public function params(...$params) {
			$this->addQuery(new Commands\ParamsCommand(...$params));
			return $this;
		}

		/**
		 * Specifies input documnets for import query
		 */
		public function inputDocuments(array $documents) {
			$this->addQuery(new Commands\InputDocumentsCommand($documents));
			return $this;
		}

	}