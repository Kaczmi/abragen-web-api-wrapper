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
	 * This object is then passed to IExecutor to create command
	 */
	class QueryServant {

		/**
		 * @var array<\AbraApi\Commands\Interfaces\ICommandQueryBuilder>
		 */
		protected array $query = [];

		/**
		 * @var array<string>
		 */
		private array $queryProperOrder = [
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
			Commands\InputDocumentsCommand::class,
			Commands\ParamsCommand::class,
			Commands\OutputDocumentCommand::class
		];

		/**
		 * Adds a query
		 */
		protected function addQuery(ICommandQueryBuilder $newQuery): void {
			if($this->query !== null && !($newQuery instanceof IMultipleCommand)) {
				foreach($this->query as $query) {
					if($query instanceof $newQuery) throw new \Exception("Cannot add two same queries of ".get_class($query));
				}
			}
			$this->query[] = $newQuery;
		}

		/**
		 * Gets whole query
		 * @return array<ICommandQueryBuilder>
		 */
		public function getQuery(): array {
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
				$properOrderedQuery = array_merge($properOrderedQuery, $actualQuery);
			}
			return $properOrderedQuery;
		}

		/**
		 * Return true if a query has specific command (instance of ..)
		 * @param mixed $command
		 */
		public function hasCommand($command): bool {
			if(!is_array($this->query)) return false;
			foreach($this->query as $query) {
				if($query instanceof $command) return true;
			}
			return false;
		}

		/**
		 * Returns specific command from query, if it is not there, returns null
		 * @param mixed $command
		 * @return mixed
		 */
		public function getQueryCommand($command) {
			if(!is_array($this->query)) return null;
			foreach($this->query as $query) {
				if($query instanceof $command) return $query;
			}
			return null;
		}

		/**
		 * Adds class command to query
		 */
		public function class(string $className): QueryServant {
			$this->addQuery(new Commands\ClassCommand($className));
			return $this;
		}

		/**
		 * Adds select command to query, parameters are optional
		 * @param string|array<string>|array<string, string>|array<int, string> ...$selects
		 */
		public function select(...$selects): QueryServant {
			$this->addQuery(new Commands\SelectCommand($selects));
			return $this;
		}

		/**
		 * Adds condition to query, uses question mark for prepared, escaped statements
		 * @param string|int|float|bool|array<mixed> ...$parameters
		 */
		public function where(string $query, ...$parameters): QueryServant {
			$this->addQuery(new Commands\WhereCommand($query, ...$parameters));
			return $this;
		}

		/**
		 * Adds condition for ID of row, accepts only one parameter, can be array
		 * @param string|array<mixed> $ids
		 */
		public function whereId($ids): QueryServant {
			// id is supposed to be 10 characters long
			if(!is_array($ids)) $ids = [ $ids ];
			foreach($ids as $id) {
				if(strlen($id) !== 10) throw new \Exception("ID of row expected, ".(($id === null) ? "NULL" : ("'".$id."'"))." given.");
			}
			return $this->where("id in (?)", $ids);
		}

		/**
		 * Adds expression query
		 * @param mixed ...$parameters
		 */
		public function expr(string $expression, ...$parameters): QueryServant {
			$this->addQuery(new Commands\ExprCommand($expression, ...$parameters));
			return $this;
		}

		/**
		 * Expands query (creates subselect)
		 * @param  string $name  it is defined as name of subselect in returned data structure
		 * @param  string $value if name is defined and expand is not subselect, this defines value of expanded field
		 */
		public function expand(string $name, ?string $value, IExecutor $executor, Interfaces\IExpandQuery $parent): Commands\ExpandCommand {
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
		public function limit(int $limit): QueryServant {
			$this->addQuery(new Commands\LimitCommand($limit));
			return $this;
		}

		/**
		 * Specific amount of rows to be skipped (for pagination for example)
		 */
		public function skip(int $skip): QueryServant {
			$this->addQuery(new Commands\SkipCommand($skip));
			return $this;
		}

		/**
		 * Orders selected rows by specified data structure
		 * @param mixed ...$orderBy
		 */
		public function orderBy(...$orderBy): QueryServant {
			$this->addQuery(new Commands\OrderByCommand(...$orderBy));
			return $this;
		}

		/**
		 * Group by command
		 * @param mixed ...$groupBy
		 */
		public function groupBy(...$groupBy): QueryServant {
			$this->addQuery(new Commands\GroupByCommand(...$groupBy));
			return $this;
		}

		/**
		 * Defines, what data are supposed to be updated/inserted
		 * @param mixed ...$data
		 */
		public function data(...$data): QueryServant {
			$this->addQuery(new Commands\DataCommand(...$data));
			return $this;
		}

		/**
		 * Specifies fulltext search
		 */
		public function fulltext(string $fulltext): QueryServant {
			$this->addQuery(new Commands\FulltextCommand($fulltext));
			return $this;
		}

		/**
		 * Specifies params (import query)
		 * @param array<string, string>|string ...$params
		 */
		public function params(...$params): QueryServant {
			$this->addQuery(new Commands\ParamsCommand(...$params));
			return $this;
		}

		/**
		 * Specifies input documnets for import query
		 * @param array<string> $documents
		 */
		public function inputDocuments(array $documents): QueryServant {
			$this->addQuery(new Commands\InputDocumentsCommand($documents));
			return $this;
		}

		/**
		 * Specifies output document data to update, for import query
		 * @param array<string, string>|string ...$data
		 */
		public function outputDocumentData(...$data): QueryServant {
			$this->addQuery(new Commands\OutputDocumentCommand(...$data));
			return $this;
		}

	}