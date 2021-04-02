<?php declare(strict_types=1);

namespace AbraApi\Commands;

class SelectCommand implements Interfaces\ICommandQueryBuilder
{

	const CLASS_SELECTOR = "select";

	/**
	 * @var array<mixed>
	 */
	private array $selects;

	/**
	 * SelectCommand constructor.
	 * @param array<mixed> $selects
	 */
	public function __construct(array $selects)
	{
		$this->processSelects($selects);
	}

	/**
	 * @param array<mixed> $selects
	 */
	public function processSelects(array $selects): void
	{
		foreach ($selects as $select) {
			if (is_array($select)) {
				foreach ($select as $name => $value) {
					if (!is_int($name)) {
						$selectQuery["name"] = $name;
						$selectQuery["value"] = $value;
						$this->selects[] = $selectQuery;
					} else {
						$this->selects[] = $value;
					}
				}
			} else {
				$this->selects[] = $select;
			}
		}
	}

	/**
	 * @return array<string, array<mixed>>
	 */
	public function getCommand(): array
	{
		$classCommand = [];
		$classCommand[self::CLASS_SELECTOR] = $this->selects;
		return $classCommand;
	}

}