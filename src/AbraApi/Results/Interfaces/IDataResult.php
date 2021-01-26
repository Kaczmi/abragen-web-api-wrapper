<?php

namespace AbraApi\Results\Interfaces;

interface IDataResult extends IResult
{
	
	public function fetch(): ?\stdClass;


	/**
	 * @return array<\stdClass>
	 */
	public function fetchAll(): array;


	/**
	 * @return mixed
	 */
	public function fetchField(string $field);


	/**
	 * @return array<mixed>
	 */
	public function fetchFlat(string $field): array;
	
}