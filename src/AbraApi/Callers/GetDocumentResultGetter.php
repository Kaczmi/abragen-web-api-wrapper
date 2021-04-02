<?php declare(strict_types=1);

namespace AbraApi\Callers;

use AbraApi\Results;

final class GetDocumentResultGetter implements Interfaces\IResultGetter
{

	private \AbraApi\Callers\Interfaces\ICaller $caller;


	public function __construct(Interfaces\ICaller $caller)
	{
		$this->caller = $caller;
	}


	/**
	 * @param array<mixed> $optHeaders
	 *
	 * @throws \AbraApi\Results\BadRequestException
	 * @throws \AbraApi\Results\NoResponseException
	 */
	public function getResult(string $url, string $body, array $optHeaders = []): Results\Interfaces\IDocumentResult
	{
		$resultPlainData = $this->caller->call($url, $body, $optHeaders);

		return (new Results\AbraApiDocumentResult($resultPlainData["content"], $resultPlainData["headers"], $resultPlainData["httpcode"]));
	}

}