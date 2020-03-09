<?php 

	declare(strict_types = 1);

	namespace AbraApi\Commands;

	class FulltextCommand implements Interfaces\ICommandQueryBuilder {

		const FULLTEXT_SELECTOR = "fulltext";

		/** @var string */
		private $fulltext;

		public function __construct(string $fulltext) {
			$this->fulltext = $fulltext;
		}

		public function getCommand(): array {
			return [ self::FULLTEXT_SELECTOR => $this->fulltext ];
		}

	}