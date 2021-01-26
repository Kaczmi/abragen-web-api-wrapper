<?php 

	declare(strict_types=1);

	namespace AbraApi\CommandBuilders;	

	use AbraApi\Executors\Interfaces\IExecutor,
		AbraApi\Commands,
		AbraApi\Callers,
		AbraApi\Results\Interfaces\IDocumentResult;

	class GetDocumentQuery extends Query {

		private Callers\GetDocumentResultGetter $resultGetter;
		private QueryServant $queryServant;
		private bool $b2b = false;
		private ?string $reportId = null;
		private ?string $exportId = null;

		public function __construct(IExecutor $executor, Callers\GetDocumentResultGetter $resultGetter) {
			$this->setExecutor($executor);
			$this->resultGetter = $resultGetter;
			$this->queryServant = new QueryServant;
		}

		/**
		 * Defines, what BO are we quering into
		 */
		public function class(string $class): GetDocumentQuery {
			$this->queryServant->class($class);
			return $this;
		}

		/**
		 * Condition
		 * @param array<string>|string $ids
		 */
		public function whereId($ids): GetDocumentQuery {
			$this->queryServant->whereId($ids);
			return $this;
		}

		/**
		 * Defines report ID
		 */
		public function report(string $id): GetDocumentQuery {
			$this->reportId = $id;
			return $this;
		}

		/**
		 * Defines export ID
		 */
		public function export(string $id): GetDocumentQuery {
			$this->exportId = $id;
			return $this;
		}

		/**
		 * Specifies, that we need to generate document using b2b export
		 */
		public function b2b(): GetDocumentQuery {
			$this->b2b = true;
			return $this;
		}

		/**
		 * Executes query and returns Results\AbraApiDocumentResult 
		 * @param  string $acceptHeader defines, what kind of document should Abra return
		 * @return  IDocumentResult return document result with specified function -> getContent
		 */
		public function execute(string $acceptHeader = "Accept: application/pdf"): IDocumentResult {
			// it is must have
			if(!($this->queryServant->hasCommand(Commands\ClassCommand::class) && $this->queryServant->hasCommand(Commands\WhereCommand::class) && ($this->reportId !== null || $this->exportId !== null)))
				throw new \Exception("To get an export or report, you need to specify class(), whereId() and report() or export()");
			// we need to make sure we are getting only one report available
			if($this->reportId !== null && $this->exportId !== null)
				throw new \Exception("You need to specify only one method - report() or export()");
			return $this->resultGetter->getResult($this->getApiEndpoint(), $this->getQuery(), [ $acceptHeader ]);
		}

		/**
		 * Returns query end point
		 */
		public function getApiEndpoint(): string {
			$query = [];
			if($this->reportId !== null) 
				$query["report"] = $this->reportId;
			if($this->exportId !== null)
				$query["export"] = $this->exportId;
			if($this->b2b)
				$query["b2b"] = "true";
			return ("query?".http_build_query($query));
		}

		/**
		 * Returns query for API
		 */
		public function getQuery(): string {
			// because it is not logical for user to make select, document query automatically adds select command
			// it can be specified without select command, but Abra API would select all columns => performance slowdown
			if(!$this->queryServant->hasCommand(Commands\SelectCommand::class))
				$this->queryServant->select("id");
			// creates JSON request
			$query = json_encode($this->executor->execute($this->queryServant));

			if($query === FALSE) {
				throw new \Exception("Could not create query");
			}

			return $query;
		}

		/**
		 * Returns PDF document data (it´s purpose is to send it to user directly via HTTP response or save it into file)
		 */
		public function getPdf(): string {
			return $this->execute("Accept: application/pdf")->getContent();
		}

		/**
		 * Returns Xlsx document
		 */
		public function getXlsx(): string {
			return $this->execute("Accept: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")->getContent();
		}

		/**
		 * Returns Csv document
		 */
		public function getCsv(): string {
			return $this->execute("Accept: text/csv")->getContent();
		}

	}