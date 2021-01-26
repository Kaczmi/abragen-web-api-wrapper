<?php 

	namespace AbraApi\Commands;

	use AbraApi\Commands\Helpers\DataQueryHelper;

	class OutputDocumentCommand implements Interfaces\ICommandQueryBuilder, Interfaces\IMultipleCommand {

		public const DATA_SELECTOR = "output_document_update";

		/** @var array<mixed> */
		private $data = [];

		/**
		 * @param mixed ...$data
		 */
		public function __construct(...$data) {
			$this->data = DataQueryHelper::processDataCommand($data);
		}

		/**
		 * @return array<string, array<mixed>>
		 */
		public function getCommand(): array {
			return [ self::DATA_SELECTOR => $this->data ];
		}

	}