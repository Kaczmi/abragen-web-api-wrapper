<?php declare(strict_types=1);

namespace AbraApi\Results\Interfaces;

interface IDocumentResult extends IResult
{

	public function getContent(): string;

}