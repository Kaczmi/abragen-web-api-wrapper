<?php declare(strict_types=1);

namespace AbraApi\CommandBuilders;

use AbraApi\Executors\Interfaces\IExecutor;

abstract class Query
{

	protected IExecutor $executor;


	public function setExecutor(IExecutor $executor): void
	{
		$this->executor = $executor;
	}

}