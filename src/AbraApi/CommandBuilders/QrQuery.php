<?php 

	namespace AbraApi\CommandBuilders;

	use AbraApi\Executors\Interfaces\IExecutor,
		AbraApi\Commands,
		AbraApi\Callers,
		AbraApi\Results\Interfaces\IQrFunctionResult;

	class QrQuery extends Query {

		private $resultGetter;
		private $queryServant;

		public function __construct(IExecutor $executor, Callers\Interfaces\IResultGetter $resultGetter) {
			$this->setExecutor($executor);
			$this->resultGetter = $resultGetter;
			$this->queryServant = new QueryServant;
		}

		public function expr($expression, ...$parameters): QrQuery {
			$this->queryServant->expr($expression, ...$parameters);
			return $this;
		}

		/**
		 * Executes expression query
		 */
		public function execute(): IQrFunctionResult {
			return $this->resultGetter->getResult("qrexpr", $this->getQuery());
		}

		/**
		 * Returns query as a JSON encoded string to be send to result getter
		 */
		public function getQuery(): string {
			if(!$this->queryServant->hasCommand(Commands\ExprCommand::class))
				throw new \Exception('You must specify QR function using ->expr($expression, ...$parameters) command.');
			return json_encode($this->executor->execute($this->queryServant));
		}

		/**
		 * Gets result of an expression
		 */
		public function getResult() {
			return $this->execute()->getResult();
		}

	}