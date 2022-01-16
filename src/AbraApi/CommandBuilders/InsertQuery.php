<?php declare(strict_types=1);

namespace AbraApi\CommandBuilders;


class InsertQuery extends Query
{

	private \AbraApi\Callers\InsertQueryResultGetter $resultGetter;

	private QueryServant $queryServant;


	public function __construct(\AbraApi\Callers\InsertQueryResultGetter $resultGetter)
	{
		$this->resultGetter = $resultGetter;
		$this->queryServant = new QueryServant;
	}


	/**
	 * Defines, what BO are we quering into
	 */
	public function class(string $class): InsertQuery
	{
		$this->queryServant->class($class);
		return $this;
	}


	/**
	 * What columns should result return
	 * If this function is not specified whilst updating data, system automatically selects only ID of updated row
	 * @param string|array<string>|array<string, string>|array<int, string> ...$selects
	 */
	public function select(...$selects): InsertQuery
	{
		$this->queryServant->select(...$selects);
		return $this;
	}


	/**
	 * What columns are supposed to be updated
	 * @param mixed ...$data
	 */
	public function data(...$data): InsertQuery
	{
		$this->queryServant->data(...$data);
		return $this;
	}


	/**
	 * Executes query and returns update data result
	 * Query uses PUT method
	 */
	public function execute(): \AbraApi\Results\Interfaces\IInsertResult
	{
		return $this->resultGetter->getResult($this->getApiEndpoint(), $this->getQuery());
	}


	/**
	 * Creates endpoint for query
	 */
	public function getApiEndpoint(): string
	{
		if (!$this->queryServant->hasCommand(\AbraApi\Commands\ClassCommand::class))
			throw new \Exception("Insert query must specify bussiness object (class)");
		return ($this->queryServant->getQueryCommand(\AbraApi\Commands\ClassCommand::class)->getClass()) . "?" . QueryHelpers::createSelectUri(
            $this->queryServant
        );
	}


	/**
	 * Merges all data commands and return it as JSON object
	 */
	public function getQuery(): string
	{
		$mergedDataCommands = QueryHelpers::mergeDataCommands($this->queryServant);
		if (\count($mergedDataCommands) === 0)
			throw new \Exception("You need to specify data() to create new Abra record.");

		$query = \json_encode($mergedDataCommands);
		if ($query === FALSE) {
			throw new \Exception("Could not create query");
		}

		return $query;
	}

}