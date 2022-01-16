<?php declare(strict_types=1);

namespace AbraApi\Results;

abstract class AbstractAbraApiResult
{

	/** @var array<mixed> */
	protected array $abraResultHeaders;

	protected int $httpCode;

	/**
	 * @var null|\stdClass|array<\stdClass>
	 */
	protected $content = NULL;


    public function getHeader(string $key): ?string
    {
        if (isset($this->abraResultHeaders[\strtolower($key)])) return $this->abraResultHeaders[\strtolower($key)];

        return NULL;
    }


    public function getHttpCode(): int
    {
        return $this->httpCode;
    }


    /**
     * @return array<mixed>
     */
    public function getHeaders(): array
    {
        return $this->abraResultHeaders;
    }


	/**
	 * @param array<mixed> $headers
	 */
	protected function parseHeaders(array $headers): void
	{
		foreach ($headers as $headerKey => $headerValue) {
			$this->abraResultHeaders[$headerKey] = $headerValue;
		}
	}


	protected function setHttpCode(int $httpCode): void
	{
		$this->httpCode = $httpCode;
		// logic for exceptions
		if ($httpCode !== 200 && $httpCode !== 201 && $httpCode !== 204) {
			switch ($httpCode) {
				case 0:
				{
					throw new \AbraApi\Results\NoResponseException("API is not responding, try to restart API´s Supervisor and Server.");
				}
				case 400:
				{
					$error = "Not-specified error occured (400) - propably some problem with Abra database consistency.";
					if (isset($this->content->description)) $error = $this->content->description;
					else if (isset($this->content->error)) $error = $this->content->error;
					throw new \AbraApi\Results\BadRequestException($error);
				}
				default:
				{
					$error = "Not-specified error (" . $httpCode . ") occured";
					if (isset($this->content->description)) $error = $this->content->description;
					else if (isset($this->content->error)) $error = $this->content->error;
					throw new \Exception($error);
				}
			}
		}
	}

}

class NoResponseException extends \Exception
{

}

class BadRequestException extends \Exception
{

}

class BadResultException extends \Exception
{

}