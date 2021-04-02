<?php declare(strict_types=1);

namespace AbraApi\Results\Interfaces;

interface IImportResult extends IResult
{

	public function getId(): string;


	public function getResult(): \stdClass;

}