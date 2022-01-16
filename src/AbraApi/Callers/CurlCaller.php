<?php declare(strict_types=1);

namespace AbraApi\Callers;

abstract class CurlCaller
{

    const QUERY_POST = "POST";
    const QUERY_PUT = "PUT";
    const QUERY_DELETE = "DELETE";
    const FORBIDDEN_CHARACTERS = [
        "\\u030c" => "",
        "\\u200b" => "",
        "\\u201" => '&quot;',
    ];


	protected \AbraApi\AbraApi $abraApi;


	/**
	 * @param array<mixed> $optHeaders
	 *
	 * @return array<string, mixed>
	 * @throws \Exception
	 */
	protected function callCurl(string $queryType, string $url, string $body, array $optHeaders = []): array
	{
		$curl = \curl_init();
		$url = $this->abraApi->getUri() . $url;
		$this->normalizeQuery($body);
		switch ($queryType) {
			case self::QUERY_PUT:
				\curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, "PUT");
				\curl_setopt($curl, \CURLOPT_POSTFIELDS, $body);
				break;
			case self::QUERY_POST:
				\curl_setopt($curl, \CURLOPT_POST, 1);
				\curl_setopt($curl, \CURLOPT_POSTFIELDS, $body);
				break;
			case self::QUERY_DELETE:
				\curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
			default:
				throw new \Exception("Unknown query type (" . $queryType . ")");
		}


		// sets authorization header to first place of headers
		\array_unshift($optHeaders, "Authorization: Basic " . $this->abraApi->getCredentials());

		\curl_setopt($curl, \CURLOPT_HTTPHEADER, $optHeaders);

		\curl_setopt($curl, \CURLOPT_URL, $url);
		\curl_setopt($curl, \CURLOPT_RETURNTRANSFER, 1);

		$headers = [];
		\curl_setopt($curl, \CURLOPT_HEADERFUNCTION,
			static function ($curl, $header) use (&$headers) {
				$len = \strlen($header);
				$header = \explode(':', $header, 2);
				if (\count($header) < 2) // ignore invalid headers
					return $len;

				$name = \strtolower(\trim($header[0]));
				if (!\array_key_exists($name, $headers))
					$headers[$name] = [\trim($header[1])];
				else
					$headers[$name][] = \trim($header[1]);

				return $len;
			}
		);

		$result = \curl_exec($curl);
		$httpcode = \curl_getinfo($curl, \CURLINFO_HTTP_CODE);
		\curl_close($curl);
		return [
			"headers" => $headers,
			"content" => $result,
			"httpcode" => $httpcode,
		];
	}


	/**
	 * There are some forbidden characters we cannot use in JSON body
	 */
	private function normalizeQuery(string &$body): void
	{
		foreach (self::FORBIDDEN_CHARACTERS as $character => $replace) {
			if (\strlen($replace) === 0) $replace = "";
			$body = \str_replace($character, $replace, $body);
		}
	}

}