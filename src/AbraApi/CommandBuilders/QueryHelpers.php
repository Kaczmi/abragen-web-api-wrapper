<?php declare(strict_types=1);

namespace AbraApi\CommandBuilders;

class QueryHelpers
{

	/**
	 * Returns string query of selected columns specified by QueryServant
	 * eg. select=id,name+as+customName,code
	 * Useful for InsertQuery and UpdateQuery
	 * If query servant does not contain SelectCommand, for performance purpose in default selects only ID
	 */
	public static function createSelectUri(QueryServant $queryServant): string
	{
		if ($queryServant->hasCommand(\AbraApi\Commands\SelectCommand::class)) {
			$selects = $queryServant->getQueryCommand(
                \AbraApi\Commands\SelectCommand::class
            )->getCommand()[\AbraApi\Commands\SelectCommand::CLASS_SELECTOR];
			$selectQuery = [];
			foreach ($selects as $select) {
				if (\is_array($select))
					$selectQuery[] = $select["value"] . " as " . $select["name"];
				else
					$selectQuery[] = $select;
			}
			return \sprintf("%s=%s", \AbraApi\Commands\SelectCommand::CLASS_SELECTOR, \implode(",", \array_map(static function ($select): string {
				return \urlencode($select);
			}, $selectQuery)));
		} else {
			return "select=id";
		}
	}


	/**
	 * Merges only data commands
	 * @return array<mixed>
	 */
	public static function mergeDataCommands(QueryServant $queryServant): array
	{
		return static::mergeCommands($queryServant, [\AbraApi\Commands\DataCommand::class]);
	}


	/**
	 * Merges all commands specified in $commandsToMerge (expected array of classes instanceof ICommandQueryBuilder)
	 * If you don´t use second parameters, in default it only merges ->data(..) commands
	 * @param array<mixed> $commandsToMerge
	 * @return array<mixed>
	 */
	public static function mergeCommands(QueryServant $queryServant, array $commandsToMerge): array
	{
		$query = $queryServant->getQuery();
		$mergedCommand = [];
		foreach ($commandsToMerge as $commandToMerge) {
			foreach ($query as $command) {
				if (
                    $command instanceof $commandToMerge
                    && $command instanceof \AbraApi\Commands\Interfaces\ICommandQueryBuilder
                )
					$mergedCommand = \array_merge_recursive($command->getCommand(), $mergedCommand);
			}
		}
		return $mergedCommand;
	}

}