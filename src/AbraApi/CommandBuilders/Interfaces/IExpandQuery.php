<?php 

	namespace AbraApi\CommandBuilders\Interfaces;

	use AbraApi\Commands\ExpandCommand;

	interface IExpandQuery {
		/**
		 * Every query which supports expand command must implement this inteface to support submerged expand queries
		 */
		public function expand(string $name, ?string $value = null): ExpandCommand;
	}