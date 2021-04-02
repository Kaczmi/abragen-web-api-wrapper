<?php declare(strict_types=1);

namespace AbraApi\Commands;

class GroupByCommand implements Interfaces\ICommandQueryBuilder
{

	public const ORDERBY_SELECTOR = "groupby";

	/**
	 * @var array<string> $groupBy
	 */
	private array $groupBy = [];

	/**
	 * @param mixed ...$groupBy
	 */
	public function __construct(...$groupBy)
	{
		$this->processGroupBy($groupBy);
	}

	/**
	 * @param array<mixed> ...$groupBy
	 */
	public function processGroupBy($groupBy): void
	{
		foreach ($groupBy as $groupColumn) {
			if (!is_string($groupColumn))
				throw new \Exception("Group by parameter is supposed to be name of column to aggregate.");
			$this->groupBy[] = $groupColumn;
		}
	}

	/**
	 * @return array<string, array<string>>
	 */
	public function getCommand(): array
	{
		$groupByCommand = [];
		$groupByCommand[self::ORDERBY_SELECTOR] = $this->groupBy;
		return $groupByCommand;
	}

}