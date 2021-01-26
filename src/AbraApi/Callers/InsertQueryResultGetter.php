<?php 

	namespace AbraApi\Callers;

	use AbraApi\Results;

	class InsertQueryResultGetter implements Interfaces\IResultGetter {

		private \AbraApi\Callers\Interfaces\ICaller $caller;

		public function __construct(Interfaces\ICaller $caller) {
			$this->caller = $caller;
		}

		public function getResult($url, $body, $optHeaders = array()): Results\Interfaces\IInsertResult {
			$resultPlainData = $this->caller->call($url, $body, $optHeaders);
			return (new Results\AbraApiInsertResult($resultPlainData["content"], $resultPlainData["headers"], $resultPlainData["httpcode"]));
		}
	}