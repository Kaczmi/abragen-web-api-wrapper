<?php 

	namespace AbraApi\Callers;

	use AbraApi\Results;

	class QrFunctionsResultGetter implements Interfaces\IResultGetter {

		private $caller;

		public function __construct(Interfaces\ICaller $caller) {
			$this->caller = $caller;
		}

		public function getResult($url, $body, $optHeaders = array()) {
			$resultPlainData = $this->caller->call($url, $body, $optHeaders);
			return (new Results\AbraApiQrFunctionResult($resultPlainData["content"], $resultPlainData["headers"], $resultPlainData["httpcode"]));
		}
	}