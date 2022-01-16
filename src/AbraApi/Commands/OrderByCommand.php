<?php declare(strict_types=1);

namespace AbraApi\Commands;

class OrderByCommand implements Interfaces\ICommandQueryBuilder
{

	public const CLASS_SELECTOR = "orderby";

	/**
	 * @var array<array<string, bool|string>>
	 */
	private array $orderBy;

	/**
	 * @param mixed ...$orderBy
	 */
	public function __construct(...$orderBy)
	{
		$this->processOrderBy($orderBy);
	}

	/**
	 * @param array<mixed> $orderBy
	 */
	public function processOrderBy($orderBy): void
	{
		foreach ($orderBy as $order) {
			if (\is_array($order)) {
				foreach ($order as $column => $desc) {
					if (\is_array($desc)) {
						throw new \Exception("OrderBy command does not support nested arrays.");
					}
					if (!\is_int($column)) {
						$this->addGroupByQuery($column, $desc);
					} else {
						$this->addGroupByQuery($desc);
					}
				}
			} else {
				$this->addGroupByQuery($order);
			}
		}
	}

    /**
     * @return array<string, array<array<string, bool|string>>>
     */
    public function getCommand(): array
    {
        $classCommand = [];
        $classCommand[self::CLASS_SELECTOR] = $this->orderBy;
        return $classCommand;
    }

	/**
	 * @param mixed $column
	 * @param mixed $desc
	 */
	private function addGroupByQuery($column, $desc = false): void
	{
		if (!\is_bool($desc)) throw new \Exception("Parameter descending of OrderByCommand must be bool value");
		if (!\is_string($column)) {
			if (\is_object($column))
				throw new \Exception("Column of orderBy command is supposed to be string, instance of " . \get_class($column) . " given");
			else
				throw new \Exception("Column of orderBy command is supposed to be string, '" . $column . "' given");
		}
		$orderByQuery["value"] = $column;
		$orderByQuery["desc"] = $desc;
		$this->orderBy[] = $orderByQuery;
	}

}