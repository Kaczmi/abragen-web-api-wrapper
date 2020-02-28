<?php 

	declare(strict_types = 1);

	namespace AbraApi\Commands;

	class GroupByCommand implements Interfaces\ICommandQueryBuilder {

		const ORDERBY_SELECTOR = "groupby";

		private $groupBy = [];

		public function __construct(...$groupBy) {
			$this->processGroupBy($groupBy);
		}

		public function processGroupBy($groupBy) {
			foreach($groupBy as $groupColumn) {
				if(!is_string($groupColumn))
					throw new \Exception("Group by parameter is supposed to be name of column to aggregate.");
				$this->groupBy[] = $groupColumn; 
			}
		}

		public function getCommand(): array {
			$groupByCommand = [];
			$groupByCommand[self::ORDERBY_SELECTOR] = $this->groupBy;
			return $groupByCommand;
		}

	}