<?php declare(strict_types=1);

namespace AbraApi\Results;

/**
 * @property \stdClass $content
 */
final class AbraApiInsertResult extends AbstractAbraApiResult implements Interfaces\IInsertResult
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


	public function getInsertedId(): string
	{
		if (!isset($this->content->id)) {
			throw new BadResultException('Insert query did not return ID');
		}

		return $this->content->id;
	}


	/**
	 * @return \stdClass
	 */
	public function getResult()
	{
		return $this->content;
	}

}