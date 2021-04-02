<?php declare(strict_types=1);

namespace AbraApi\Results;

final class AbraApiGetResult extends AbstractAbraApiResult implements Interfaces\IDataResult
{
	/** @var array<\stdClass> */
	private array $abraResultRows;

	private int $fetchCount = 0;


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
		$this->content = $this->abraResultRows = json_decode($result);
	}


	/**
	 * Returns one specific column from result
	 */
	public function fetchField(string $field)
	{
		$row = $this->fetch();
		if (isset($row->$field)) return $row->$field;

		return NULL;
	}


	/**
	 * Fetches single row or returns null
	 */
	public function fetch(): ?\stdClass
	{
		if (count($this->abraResultRows) > $this->fetchCount) {
			return $this->abraResultRows[$this->fetchCount++];
		}

		return NULL;
	}


	/**
	 * Get´s full response returned by API
	 */
	public function fetchAll(): array
	{
		return $this->abraResultRows;
	}


	/**
	 * Returns flat array of specific field
	 */
	public function fetchFlat(string $field): array
	{
		$rtnArray = [];
		foreach ($this->abraResultRows as $row) {
			if (isset($row->$field)) $rtnArray[] = $row->$field;
		}

		return $rtnArray;
	}


}