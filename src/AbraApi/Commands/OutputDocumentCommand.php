<?php 

	namespace AbraApi\Commands;

	use AbraApi\Commands\Helpers\DataQueryHelper;

	class OutputDocumentCommand implements Interfaces\ICommandQueryBuilder, Interfaces\IMultipleCommand {

		const DATA_SELECTOR = "output_document_update";
		/** @var array */
		private $data = [];

		public function __construct(...$data) {
			$this->data = DataQueryHelper::processDataCommand($data);
		}

		public function getCommand(): array {
			return [ self::DATA_SELECTOR => $this->data ];
		}

	}