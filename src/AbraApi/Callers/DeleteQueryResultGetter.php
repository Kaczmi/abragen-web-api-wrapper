<?php 

	namespace AbraApi\Callers;

	use AbraApi\Results;

	class DeleteQueryResultGetter implements Interfaces\IResultGetter {

		private \AbraApi\Callers\Interfaces\ICaller $caller;

		public function __construct(\AbraApi\Callers\Interfaces\ICaller $caller) {
			$this->caller = $caller;
		}

		public function getResult(string $url, string $body, array $optHeaders = []): Results\Interfaces\IDeleteResult {
			$resultPlainData = $this->caller->call($url, "", $optHeaders); // for deleting content we do not need any body so we ignore it
			return (new Results\AbraApiDeleteResult($resultPlainData["content"], $resultPlainData["headers"], $resultPlainData["httpcode"]));
		}
	}