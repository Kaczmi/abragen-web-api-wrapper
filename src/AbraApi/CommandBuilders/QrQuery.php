<?php declare(strict_types=1);

namespace AbraApi\CommandBuilders;

use AbraApi\Executors\Interfaces\IExecutor,
	AbraApi\Commands,
	AbraApi\Callers,
	AbraApi\Results\Interfaces\IQrFunctionResult;

class QrQuery extends Query
{

	private Callers\QrFunctionsResultGetter $resultGetter;
	private QueryServant $queryServant;

	public function __construct(IExecutor $executor, Callers\QrFunctionsResultGetter $resultGetter)
	{
		$this->setExecutor($executor);
		$this->resultGetter = $resultGetter;
		$this->queryServant = new QueryServant;
	}

	/**
	 * @param mixed ...$parameters
	 */
	public function expr(string $expression, ...$parameters): QrQuery
	{
		$this->queryServant->expr($expression, ...$parameters);
		return $this;
	}

	/**
	 * Executes expression query
	 */
	public function execute(): IQrFunctionResult
	{
		return $this->resultGetter->getResult("qrexpr", $this->getQuery());
	}

	/**
	 * Returns query as a JSON encoded string to be send to result getter
	 */
	public function getQuery(): string
	{
		if (!$this->queryServant->hasCommand(Commands\ExprCommand::class))
			throw new \Exception('You must specify QR function using ->expr($expression, ...$parameters) command.');

		$query = \json_encode($this->executor->execute($this->queryServant));

		if ($query === FALSE) {
			throw new \Exception("Could not create query");
		}

		return $query;
	}

	/**
	 * Gets result of an expression
	 * @return mixed
	 */
	public function getResult()
	{
		return $this->execute()->getResult();
	}

}