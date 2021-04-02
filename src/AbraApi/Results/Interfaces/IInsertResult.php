<?php declare(strict_types=1);

namespace AbraApi\Results\Interfaces;

interface IInsertResult extends IResult
{

	public function getInsertedId(): string;

}