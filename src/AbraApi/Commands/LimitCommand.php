<?php 

	declare(strict_types = 1);

	namespace AbraApi\Commands;

	class LimitCommand implements Interfaces\ICommandQueryBuilder {

		const CLASS_SELECTOR = "take";

		private $limit;

		public function __construct($limit) {
			if(!is_integer($limit)) throw new \Exception("Limit must be an integer value.");
			$this->limit = intval($limit);
		}

		public function getCommand() {
			$classCommand = [];
			$classCommand[self::CLASS_SELECTOR] = $this->limit;
			return $classCommand;
		}

	}