<?php 

	namespace AbraApi\Callers;

	use AbraApi\Results;

	class ImportQueryResultGetter implements Interfaces\IResultGetter {
		/** @var \AbraApi\AbraApi */
		private $abraApi;
		/** @var bool */
		private $usePutMethod = false;

		public function __construct(\AbraApi\AbraApi $abraApi) {
			$this->abraApi = $abraApi;
		}

		public function getCaller(): Interfaces\ICaller {
			if($this->usePutMethod)
				return (new PutCaller($this->abraApi));
			return (new PostCaller($this->abraApi));
		}

		/**
		 * Use PUT method to create query
		 */
		public function usePutMethod() {
			$this->usePutMethod = true;
		}

		public function getResult($url, $body, $optHeaders = array()) {
			$caller = $this->getCaller();
			$resultPlainData = $caller->call($url, $body, $optHeaders);
			return (new Results\AbraApiImportResult($resultPlainData["content"], $resultPlainData["headers"], $resultPlainData["httpcode"]));
		}
	}