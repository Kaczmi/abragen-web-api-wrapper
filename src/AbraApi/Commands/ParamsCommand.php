<?php 

	namespace AbraApi\Commands;

	use AbraApi\Commands\Helpers\DataQueryHelper;

	class ParamsCommand implements Interfaces\ICommandQueryBuilder, Interfaces\IMultipleCommand {

		public const PARAMS_SELECTOR = "params";
		/** @var array<mixed> */
		private array $params = [];

		/**
		 * @param mixed ...$params
		 */
		public function __construct(...$params) {
			$this->params = DataQueryHelper::processDataCommand($params);
		}

		/**
		 * @return array<string, array<mixed>>
		 */
		public function getCommand(): array {
			return [ self::PARAMS_SELECTOR => $this->params ];
		}

	}