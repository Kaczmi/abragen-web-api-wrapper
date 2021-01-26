<?php 

	namespace AbraApi\Callers;

	class DeleteCaller extends CurlCaller implements Interfaces\ICaller {

		public function __construct(\AbraApi\AbraApi $abraApi) {
			$this->abraApi = $abraApi;
		}


		/**
		 * @param string $url
		 * @param string $body
		 * @param array<mixed> $optHeaders
		 *
		 * @return array<string, mixed>
		 * @throws \Exception
		 */
		public function call(string $url, string $body, array $optHeaders = []): array
		{
			return $this->callCurl(self::QUERY_DELETE, $url, "", $optHeaders);
		}
		
	}