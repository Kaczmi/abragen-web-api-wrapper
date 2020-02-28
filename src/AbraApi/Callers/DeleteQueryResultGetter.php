<?php 

	namespace AbraApi\Callers;

	use AbraApi\Results;

	class DeleteQueryResultGetter implements Interfaces\IResultGetter {

		private $caller;

		public function __construct(Interfaces\ICaller $caller) {
			$this->caller = $caller;
		}

		public function getResult($url, $body, $optHeaders = array()) {
			$resultPlainData = $this->caller->call($url, "", $optHeaders); // for deleting content we do not need any body so we ignore it
			return (new Results\AbraApiDeleteResult($resultPlainData["content"], $resultPlainData["headers"], $resultPlainData["httpcode"]));
		}
	}