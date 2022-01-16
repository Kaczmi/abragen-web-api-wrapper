<?php declare(strict_types=1);

namespace AbraApi\CommandBuilders;

use AbraApi\Executors\Interfaces\IExecutor,
	AbraApi\Callers,
	AbraApi\Results\Interfaces\IDataResult,
	AbraApi\Commands\ExpandCommand;

class GetQuery extends Query implements Interfaces\IExpandQuery
{

	private Callers\GetQueryResultGetter $resultGetter;

	private QueryServant $queryServant;


	public function __construct(IExecutor $executor, Callers\GetQueryResultGetter $resultGetter)
	{
		$this->setExecutor($executor);
		$this->resultGetter = $resultGetter;
		$this->queryServant = new QueryServant;
	}


	/**
	 * Defines, what BO are we quering into
	 */
	public function class(string $class): GetQuery
	{
		$this->queryServant->class($class);
		return $this;
	}


	/**
	 * Fulltext search
	 */
	public function fulltext(string $fulltext): GetQuery
	{
		$this->queryServant->fulltext($fulltext);
		return $this;
	}


	/**
	 * What columns must query return
	 * @param string|array<string>|array<string, string>|array<int, string> ...$selects
	 */
	public function select(...$selects): GetQuery
	{
		$this->queryServant->select(...$selects);
		return $this;
	}


	/**
	 * Condition
	 * @param string|int|float|bool|array<mixed> ...$parameters
	 */
	public function where(string $query, ...$parameters): GetQuery
	{
		$this->queryServant->where($query, ...$parameters);
		return $this;
	}


	/**
	 * Condition, specific for ID
	 * @param array<string>|string $ids
	 */
	public function whereId($ids): GetQuery
	{
		$this->queryServant->whereId($ids);
		return $this;
	}


	/**
	 * Creates subselect
	 */
	public function expand(string $name, ?string $value = null): ExpandCommand
	{
		return $this->queryServant->expand($name, $value, $this->executor, $this);
	}


	/**
	 * Limit of rows to be selected
	 */
	public function limit(int $limit): GetQuery
	{
		$this->queryServant->limit($limit);
		return $this;
	}


	/**
	 * Amount of skipped rows
	 */
	public function skip(int $skip): GetQuery
	{
		$this->queryServant->skip($skip);
		return $this;
	}


	/**
	 * Creates subselect
	 * @param mixed ...$orderBy
	 */
	public function orderBy(...$orderBy): GetQuery
	{
		$this->queryServant->orderBy($orderBy);
		return $this;
	}


	/**
	 * Creates groupby aggregation
	 * @param mixed ...$groupBy
	 */
	public function groupBy(...$groupBy): GetQuery
	{
		$this->queryServant->groupBy(...$groupBy);
		return $this;
	}


	/**
	 * Executes query and returns data result
	 */
	public function execute(): IDataResult
	{
		return $this->resultGetter->getResult("query", $this->getQuery());
	}


	/**
	 * Returns query as a string to be send to AbraApiWorker
	 */
	public function getQuery(): string
	{
		$queryBody = $this->executor->execute($this->queryServant);

		$query = \json_encode($queryBody);
		if ($query === FALSE) {
			throw new \Exception("Could not create query");
		}

		return $query;
	}


	/**
	 * Fetches first row returned by Abra
	 */
	public function fetch(): ?\stdClass
	{
		return $this->execute()->fetch();
	}


	/**
	 * Fetches specific field in first row returned by Abra
	 * @return mixed
	 */
	public function fetchField(string $field)
	{
		return $this->execute()->fetchField($field);
	}


	/**
	 * Fetches all rows returned by Abra as JSON object
	 * @return array<\stdClass>
	 */
	public function fetchAll(): array
	{
		return $this->execute()->fetchAll();
	}


	/**
	 * Returns specific field ($field) as flat array ([1, 2, 3, ...])
	 * E.g. you want to get all ID´s of invoices for specific firm, and you want it in 1-dimensional array
	 * Result could be this for example: [ { id: 1 }, { id: 2 }, { id: 3} ]
	 * You use $command->select....->fetchFlat("id") and you get [1, 2, 3]
	 * @return array<mixed>
	 */
	public function fetchFlat(string $field): array
	{
		return $this->execute()->fetchFlat($field);
	}

}