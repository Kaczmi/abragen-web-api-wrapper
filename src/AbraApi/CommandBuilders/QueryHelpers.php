<?php 

	namespace AbraApi\CommandBuilders;

	use AbraApi\Commands;

	class QueryHelpers {

		/**
		 * Returns string query of selected columns specified by QueryServant
		 * eg. select=id,name+as+customName,code
		 * Useful for InsertQuery and UpdateQuery
		 * If query servant does not contain SelectCommand, for performance purpose in default selects only ID
		 */
		public static function createSelectUri(QueryServant $queryServant): string {
			if($queryServant->hasCommand(Commands\SelectCommand::class)) {
				$selects = $queryServant->getQueryCommand(Commands\SelectCommand::class)->getCommand()[Commands\SelectCommand::CLASS_SELECTOR];
				$selectQuery = [];
				foreach($selects as $select) {
					if(is_array($select)) 
						$selectQuery[] = $select["value"]." as ".$select["name"];
					else
						$selectQuery[] = $select;
				}
				return sprintf("%s=%s", Commands\SelectCommand::CLASS_SELECTOR, implode(",", array_map(function($select) {
					return urlencode($select);
				}, $selectQuery)));
			}
			else {
				return "select=id";
			}
		}

		/**
		 * Merges all data commands into one array object, valid for AbraGen API
		 * @return array
		 */
		public static function mergeDataCommands(QueryServant $queryServant): array {
			$query = $queryServant->getQuery();
			$dataCommands = [];
			array_map(function($command) use(&$dataCommands) { 
				if($command instanceof Commands\DataCommand) 
					$dataCommands = array_merge($command->getCommand(), $dataCommands); 
				return $command; 
			}, $query);
			return $dataCommands;
		}
		
	}