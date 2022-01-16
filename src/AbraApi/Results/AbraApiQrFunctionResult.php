<?php declare(strict_types=1);

namespace AbraApi\Results;

final class AbraApiQrFunctionResult extends AbstractAbraApiResult implements Interfaces\IQrFunctionResult
{

	/**
	 * @param array<mixed> $headers
	 *
	 * @throws \AbraApi\Results\BadRequestException
	 * @throws \AbraApi\Results\NoResponseException
	 */
	public function __construct(string $result, array $headers, int $httpCode)
	{
		$this->parseResult($result);
		$this->parseHeaders($headers);
		$this->setHttpCode($httpCode);
	}


	private function parseResult(string $result): void
	{
		$this->content = json_decode($result);
	}


	public function getResult()
	{
		if (!isset($this->content->result)) {
			throw new BadResultException('QR Function did not return result.');
		}

		return $this->content->result;
	}

}