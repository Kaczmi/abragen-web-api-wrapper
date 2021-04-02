<?php declare(strict_types=1);

namespace AbraApi\Executors\Interfaces;

use AbraApi\CommandBuilders\QueryServant;

interface IExecutor
{

	/**
	 * Execute command is supposed to create JSON query
	 * @return array<mixed>
	 */
	public function execute(QueryServant $queryServant): array;
}