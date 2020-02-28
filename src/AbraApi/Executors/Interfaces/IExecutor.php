<?php 

	namespace AbraApi\Executors\Interfaces;

	use AbraApi\CommandBuilders\QueryServant;

	interface IExecutor {
		/** 
		 * Execute command is supposed to create JSON query
		 */
		public function execute(QueryServant $queryServant);
	}