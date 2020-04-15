<?php 

	namespace AbraApi\CommandBuilders;

	use AbraApi\Commands;
	use AbraApi\Commands\Interfaces\ICommandQueryBuilder;

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
		 * Merges only data commands
		 */
		public static function mergeDataCommands(QueryServant $queryServant): array {
			return static::mergeCommands($queryServant, [ Commands\DataCommand::class ]);
		}

		/**
		 * Merges all commands specified in $commandsToMerge (expected array of classes instanceof ICommandQueryBuilder)
		 * If you don´t use second parameters, in default it only merges ->data(..) commands
		 * @return array
		 */
		public static function mergeCommands(QueryServant $queryServant, array $commandsToMerge): array {
			$query = $queryServant->getQuery();
			$mergedCommand = [];
			foreach($commandsToMerge as $commandToMerge) {
				foreach($query as $command) {
					if($command instanceof $commandToMerge) 
						$mergedCommand = array_merge_recursive($command->getCommand(), $mergedCommand); 
				}
			}
			return $mergedCommand;
		}
		
	}