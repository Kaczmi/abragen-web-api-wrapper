<?php declare(strict_types=1);

namespace AbraApi\Commands;

class ClassCommand implements Interfaces\ICommandQueryBuilder
{

	const CLASS_SELECTOR = "class";

	/** @var string */
	private $className;

	public function __construct(string $className)
	{
		if (strlen($className) === 0)
			throw new \Exception("BO name expected, empty string given.");
		$this->className = $className;
	}

	public function getClass(): string
	{
		return trim($this->className);
	}

	public function getCommand(): array
	{
		return [self::CLASS_SELECTOR => $this->getClass()];
	}

}