<?php 

	namespace AbraApi\Callers;

	class PostCaller extends CurlCaller implements Interfaces\ICaller {

		public function __construct(\AbraApi\AbraApi $abraApi) {
			$this->abraApi = $abraApi;
		}

		public function call($url, $body, $optHeaders = array()) {
			return $this->callCurl(self::QUERY_POST, $url, $body, $optHeaders);
		}
		
	}