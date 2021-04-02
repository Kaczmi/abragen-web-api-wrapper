<?php declare(strict_types=1);

namespace AbraApi\Results\Interfaces;

interface IQrFunctionResult extends IResult
{

	/**
	 * @return mixed
	 */
	public function getResult();

}