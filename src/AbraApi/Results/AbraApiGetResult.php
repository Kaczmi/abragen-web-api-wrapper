<?php 

	namespace AbraApi\Results;

	class AbraApiGetResult extends AbstractAbraApiResult implements Interfaces\IDataResult {

		private $abraResultRows;

		private $fetchCount = 0;

		public function __construct($result, $headers, $httpCode) {
			$this->parseResult($result);
			$this->parseHeaders($headers);			
			$this->setHttpCode($httpCode);
		}

		private function parseResult($result) {
			$this->content = $this->abraResultRows = json_decode($result);
		}

		/**
		 * Returns one specific column from result
		 */
		public function fetchField($field) {
			$row = $this->fetch();
			if(isset($row->$field)) return $row->$field;
			return null;
		}

		/**
		 * Fetches single row or returns null
		 */
		public function fetch() {
			if(count($this->abraResultRows) > $this->fetchCount) {
				return $this->abraResultRows[$this->fetchCount++];
			}
			return null;
		}

		/**
		 * Get´s full response returned by API
		 */
		public function fetchAll() {
			return $this->abraResultRows;
		}

		/**
		 * Returns flat array of specific field
		 */
		public function fetchFlat($field) {
			$rtnArray = [];
			foreach($this->abraResultRows as $row) {
				if(isset($row->$field)) $rtnArray[] = $row->$field;
			}
			return $rtnArray;
		}


	}