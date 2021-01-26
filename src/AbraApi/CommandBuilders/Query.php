<?php 

	namespace AbraApi\CommandBuilders;

	use AbraApi\Executors\Interfaces\IExecutor;

	abstract class Query {

		protected IExecutor $executor;

		public function setExecutor(IExecutor $executor): void {
			$this->executor = $executor;
		}

	}