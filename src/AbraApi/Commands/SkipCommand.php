<?php 

	declare(strict_types = 1);

	namespace AbraApi\Commands;

	class SkipCommand implements Interfaces\ICommandQueryBuilder {

		const CLASS_SELECTOR = "skip";

		private $skip;

		public function __construct($skip) {
			if(!is_integer($skip)) throw new \Exception("Skip value must be an integer.");
			$this->skip = intval($skip);
		}

		public function getCommand() {
			$classCommand = [];
			$classCommand[self::CLASS_SELECTOR] = $this->skip;
			return $classCommand;
		}

	}