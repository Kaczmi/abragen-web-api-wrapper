<?php 

	namespace AbraApi\Executors;

	use AbraApi\Executors\Interfaces,
		AbraApi\Commands\Interfaces\ICommandQueryBuilder,
		AbraApi\CommandBuilders\QueryServant;

	final class JsonExecutor implements Interfaces\IExecutor {

		public function execute(QueryServant $queryServant): array {
			$jsonQuery = [];
			$query = $queryServant->getQuery();
			foreach($query as $command) {
				if(!$command instanceof ICommandQueryBuilder) throw new \Exception("Query ".get_class($command)." must be instanceof ICommandQueryBuilder");
				if($command instanceof \AbraApi\Commands\ExpandCommand) $jsonQuery["select"][] = $command->getCommand();
				else $jsonQuery = array_merge($jsonQuery, $command->getCommand());
			}
			return $jsonQuery;
		}
 
	}