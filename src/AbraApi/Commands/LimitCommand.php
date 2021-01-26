<?php 

	declare(strict_types = 1);

	namespace AbraApi\Commands;

	class LimitCommand implements Interfaces\ICommandQueryBuilder {

		public const CLASS_SELECTOR = "take";

		private int $limit;

		public function __construct(int $limit) {
			$this->limit = $limit;
		}

		/**
		 * @return array<string, int>
		 */
		public function getCommand(): array {
			$classCommand = [];
			$classCommand[self::CLASS_SELECTOR] = $this->limit;
			return $classCommand;
		}

	}