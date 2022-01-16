<?php declare(strict_types=1);

namespace AbraApi\Callers;

use AbraApi\Results;

class QrFunctionsResultGetter implements Interfaces\IResultGetter
{

	private \AbraApi\Callers\Interfaces\ICaller $caller;


	public function __construct(Interfaces\ICaller $caller)
	{
		$this->caller = $caller;
	}


	public function getResult($url, $body, $optHeaders = array()): Results\Interfaces\IQrFunctionResult
	{
		$resultPlainData = $this->caller->call($url, $body, $optHeaders);
		return (new Results\AbraApiQrFunctionResult($resultPlainData["content"], $resultPlainData["headers"], $resultPlainData["httpcode"]));
	}

}