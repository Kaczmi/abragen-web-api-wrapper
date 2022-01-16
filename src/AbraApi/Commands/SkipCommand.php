<?php declare(strict_types=1);

namespace AbraApi\Commands;

class SkipCommand implements Interfaces\ICommandQueryBuilder
{

	public const CLASS_SELECTOR = "skip";


	private int $skip;


	public function __construct(int $skip)
	{
		$this->skip = $skip;
	}


	/**
	 * @return array<string, int>
	 */
	public function getCommand(): array
	{
		$classCommand = [];
		$classCommand[self::CLASS_SELECTOR] = $this->skip;
		return $classCommand;
	}

}