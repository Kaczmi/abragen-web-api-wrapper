<?php 

	namespace AbraApi\Callers;

	class PostCaller extends CurlCaller implements Interfaces\ICaller {

		public function __construct(\AbraApi\AbraApi $abraApi) {
			$this->abraApi = $abraApi;
		}

		public function call(string $url, string $body, array $optHeaders = []): array
		{
			return $this->callCurl(self::QUERY_POST, $url, $body, $optHeaders);
		}
		
	}