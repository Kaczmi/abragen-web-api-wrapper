<?php 

	namespace AbraApi\Commands;

	use AbraApi\Commands\Helpers\DataQueryHelper;

	class DataCommand implements Interfaces\ICommandQueryBuilder, Interfaces\IMultipleCommand {

		private $data = [];

		public function __construct(...$data) {
			$this->data = DataQueryHelper::processDataCommand($data);
		}

		public function getCommand(): array {
			return $this->data;
		}

	}