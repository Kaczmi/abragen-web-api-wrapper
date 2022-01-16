<?php declare(strict_types=1);

namespace AbraApi\Callers;

class PutCaller extends CurlCaller implements Interfaces\ICaller
{

	public function __construct(\AbraApi\AbraApi $abraApi)
	{
		$this->abraApi = $abraApi;
	}


	public function call(string $url, string $body, array $optHeaders = []): array
	{
		return $this->callCurl(self::QUERY_PUT, $url, $body, $optHeaders);
	}

}