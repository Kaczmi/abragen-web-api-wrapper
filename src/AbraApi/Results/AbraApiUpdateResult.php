<?php declare(strict_types=1);

namespace AbraApi\Results;

final class AbraApiUpdateResult extends AbstractAbraApiResult implements Interfaces\IUpdateResult
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
	
	
	public function getUpdatedId(): string
	{
		return $this->content->id;
	}
	
	
	public function getResult(): \stdClass
	{
		return $this->content;
	}
	
}