<?php 

	namespace AbraApi\Commands;

	use AbraApi\Commands\Helpers\DataQueryHelper;

	class ParamsCommand implements Interfaces\ICommandQueryBuilder, Interfaces\IMultipleCommand {

		const PARAMS_SELECTOR = "params";
		/** @var array */
		private $params = [];

		public function __construct(...$params) {
			$this->params = DataQueryHelper::processDataCommand($params);
		}

		public function getCommand(): array {
			return [ self::PARAMS_SELECTOR => $this->data ];
		}

	}