<?php 

	namespace AbraApi\Commands;

	class DataCommand implements Interfaces\ICommandQueryBuilder {

		private $data = [];

		public function __construct(...$data) {
			$this->processData($data, $this->data);
		}

		/**
		 * There are two available ways of using data command
		 * You can specify column and new value this way:
		 * ->data("name", "xxx")
		 * which returns in this function one-dimensional array with two values
		 * Or you can specify multiple colums with values in array, where key is column name and value is new value
		 * Array value can also be an array
		 * ->data(["name" => "xxx", "rows" => [ "storecard_id" => "1234567890" ]], [ "firm_id" => "xxxxxxx" ], ...)
		 */
		private function processData($dataToProcess, &$data) {
			if(count($dataToProcess) === 2) {
				if(isset($dataToProcess[0]) && isset($dataToProcess[1]) && !is_array($dataToProcess[0]) && !is_array($dataToProcess[1])) {
					// this is simple data command, only column - value
					$data[$dataToProcess[0]] = $dataToProcess[1];
					return;
				}
			}
			foreach($dataToProcess as $pr) {
				if(!is_array($pr)) {
					if(is_object($pr))
						throw new \Exception("Data command parameter - array was expected, instance of ".get_class($pr)." given");
					else
						throw new \Exception("Data command parameter - array was expected, '".$pr."' given");
				}
				$data = array_merge($data, $pr);
			}
		}
		
		public function getCommand(): array {
			return $this->data;
		}

	}