<?php declare(strict_types=1);

namespace AbraApi\Results;

/**
 * @property \stdClass $content
 */
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

    public function getUpdatedId(): string
    {
        if (!isset($this->content->id)) {
            throw new \AbraApi\Results\BadResultException('Update query did not return ID');
        }

        return $this->content->id;
    }

    public function getResult(): \stdClass
    {
        return $this->content;
    }

	private function parseResult(string $result): void
	{
		$this->content = \json_decode($result);
	}

}