<?php 

	namespace AbraApi\CommandBuilders;

	use AbraApi\Executors\Interfaces\IExecutor;

	abstract class Query {

		protected $executor;

		public function setExecutor(IExecutor $executor) {
			$this->executor = $executor;
		}

	}