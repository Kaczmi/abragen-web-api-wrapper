<?php 

	declare(strict_types = 1);

	namespace AbraApi\Commands;

	class SelectCommand implements Interfaces\ICommandQueryBuilder {

		const CLASS_SELECTOR = "select";

		private $className;

		private $selects;

		public function __construct($selects) {
			$this->processSelects($selects);
		}

		public function processSelects($selects) {
			foreach($selects as $select) {
				if(is_array($select)) {
					foreach($select as $name => $value) {
						if(!is_int($name)) {
							$selectQuery["name"] = $name;
							$selectQuery["value"] = $value;
							$this->selects[] = $selectQuery;
						}
						else {
							$this->selects[] = $value;
						}
					}
				}
				else {
					$this->selects[] = $select;
				}
			}
		}

		public function getCommand(): array {
			$classCommand = [];
			$classCommand[self::CLASS_SELECTOR] = $this->selects;
			return $classCommand;
		}

	}