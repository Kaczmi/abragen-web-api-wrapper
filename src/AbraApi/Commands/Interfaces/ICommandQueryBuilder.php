<?php

namespace AbraApi\Commands\Interfaces;

interface ICommandQueryBuilder
{

	/**
	 * @return array<mixed>
	 */
	public function getCommand(): array;

}