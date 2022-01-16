<?php declare(strict_types=1);

namespace AbraApi\Commands;

use AbraApi\Executors\Interfaces\IExecutor,
	AbraApi\CommandBuilders\Interfaces\IExpandQuery,
	AbraApi\CommandBuilders\QueryServant,
	AbraApi\Commands\ClassCommand;

class ExpandCommand implements Interfaces\ICommandQueryBuilder, IExpandQuery, Interfaces\IMultipleCommand
{

	private IExpandQuery $parentQuery;

	private string $name;

	private string $value;

	private QueryServant $queryServant;

	private IExecutor $executor;


	/**
	 * Every command which support expand command must implement IExpandQuery to support submerged queries
	 */
	public function __construct(IExpandQuery $parentQuery, string $name, string $value, IExecutor $executor)
	{
		$this->parentQuery = $parentQuery;
		$this->name = $name;
		$this->value = $value;
		$this->executor = $executor;
		$this->queryServant = new QueryServant;
	}


	/**
	 * Defines, what BO are we quering into
	 */
	public function class(string $class): ExpandCommand
	{
		$this->queryServant->class($class);
		return $this;
	}


	/**
	 * Fulltext search
	 */
	public function fulltext(string $fulltext): ExpandCommand
	{
		$this->queryServant->fulltext($fulltext);
		return $this;
	}


	/**
	 * What columns must query return
	 * @param string|array<string>|array<string, string>|array<int, string> ...$selects
	 */
	public function select(...$selects): ExpandCommand
	{
		$this->queryServant->select(...$selects);
		return $this;
	}


	/**
	 * Condition for expanded command
	 * @param string|int|float|bool|array<mixed> ...$parameters
	 */
	public function where(string $query, ...$parameters): ExpandCommand
	{
		$this->queryServant->where($query, ...$parameters);
		return $this;
	}


	/**
	 * Limit of selected rows
	 */
	public function limit(int $limit): ExpandCommand
	{
		$this->queryServant->limit($limit);
		return $this;
	}


	/**
	 * Amount of skipped rows
	 */
	public function skip(int $skip): ExpandCommand
	{
		$this->queryServant->skip($skip);
		return $this;
	}


	/**
	 * Orders selected rows by specified data structure
	 * @param string|array<string>|array<string, bool> ...$orderBy
	 */
	public function orderBy(...$orderBy): ExpandCommand
	{
		$this->queryServant->orderBy(...$orderBy);
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
	 * Creates groupby aggregation
	 * @param string|array<string>|array<string, bool> ...$groupBy
	 */
	public function groupBy(...$groupBy): ExpandCommand
	{
		$this->queryServant->groupBy(...$groupBy);
		return $this;
	}


	/**
	 * Ends expand query and jumps on to parent query
	 */
	public function end(): IExpandQuery
	{
		return $this->parentQuery;
	}


	/**
	 * @return array<mixed>
	 */
	public function getCommand(): array
	{
		$expandCommand = [];
		$expandCommand["name"] = $this->name;
		$query = $this->execute();
		if ($this->queryServant->hasCommand(\AbraApi\Commands\ClassCommand::class)) {
			$expandCommand["value"] = $query;
		} else {
			$expandCommand["value"]["field"] = $this->value;
			$expandCommand["value"]["query"] = $query;
		}
		return $expandCommand;
	}


	/**
	 * @return array<mixed>
	 */
	private function execute(): array
	{
		return $this->executor->execute($this->queryServant);
	}

}