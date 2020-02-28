<?php 

	namespace AbraApi\Callers;

	use AbraApi\Results;

	class UpdateQueryResultGetter implements Interfaces\IResultGetter {

		private $caller;

		public function __construct(Interfaces\ICaller $caller) {
			$this->caller = $caller;
		}

		public function getResult($url, $body, $optHeaders = array()) {
			$resultPlainData = $this->caller->call($url, $body, $optHeaders);
			return (new Results\AbraApiUpdateResult($resultPlainData["content"], $resultPlainData["headers"], $resultPlainData["httpcode"]));
		}
	}