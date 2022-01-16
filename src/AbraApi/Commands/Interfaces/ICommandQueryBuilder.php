<?php declare(strict_types=1);

namespace AbraApi\Commands\Interfaces;

interface ICommandQueryBuilder
{

	/**
	 * @return array<mixed>
	 */
	public function getCommand(): array;

}