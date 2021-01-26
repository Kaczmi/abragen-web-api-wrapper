<?php declare(strict_types=1);

namespace AbraApi\Results;

final class AbraApiDocumentResult extends AbstractAbraApiResult implements Interfaces\IDocumentResult
{
	
	/**
	 * @param array<mixed>  $headers
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
		$this->content = new \stdClass();
		$this->content->document = $result;
	}
	
	
	public function getContent(): string
	{
		return $this->content->document;
	}
	
}