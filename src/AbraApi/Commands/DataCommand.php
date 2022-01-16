<?php declare(strict_types=1);

namespace AbraApi\Commands;

use AbraApi\Commands\Helpers\DataQueryHelper;

class DataCommand implements Interfaces\ICommandQueryBuilder, Interfaces\IMultipleCommand
{

	/**
	 * @var array<mixed>
	 */
	private array $data = [];


	/**
	 * @param mixed ...$data
	 */
	public function __construct(...$data)
	{
		$this->data = DataQueryHelper::processDataCommand($data);
	}


	/**
	 * @return array<mixed>
	 */
	public function getCommand(): array
	{
		return $this->data;
	}

}