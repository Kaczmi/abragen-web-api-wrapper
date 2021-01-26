<?php 

	namespace AbraApi\Callers;

	use AbraApi\Results;

	class UpdateQueryResultGetter implements Interfaces\IResultGetter {

		private \AbraApi\Callers\Interfaces\ICaller $caller;

		public function __construct(Interfaces\ICaller $caller) {
			$this->caller = $caller;
		}

		public function getResult($url, $body, $optHeaders = array()): Results\Interfaces\IUpdateResult {
			$resultPlainData = $this->caller->call($url, $body, $optHeaders);
			return (new Results\AbraApiUpdateResult($resultPlainData["content"], $resultPlainData["headers"], $resultPlainData["httpcode"]));
		}
	}