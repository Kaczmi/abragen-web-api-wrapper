<?php declare(strict_types=1);

namespace AbraApi\Commands;

class WhereCommand implements Interfaces\ICommandQueryBuilder
{

	public const CLASS_SELECTOR = "where";

	private string $condition;

	/**
	 * @param string $query
	 * @param mixed ...$params
	 */
	public function __construct(string $query, ...$params)
	{
		$this->processCondition($query, $params);
	}

	/**
	 * @param array<mixed> $params
	 */
	public function processCondition(string $query, array $params): void
	{
		// at first, remove double quotes and replace them to simple quotes
		$query = str_replace('"', "'", $query);
		$this->condition = $query;
		$paramsPosition = 0;
		$placeholdersCount = 0;
		$parametersCount = count($params);
		while (($pos = strpos($this->condition, "?")) !== false) {
			if (!isset($params[$paramsPosition])) throw new \Exception("There is a missing parameter in condition");
			$paramValue = $params[$paramsPosition];
			if (is_array($paramValue)) {
				// array value in condition - used for in(..) condition
				// must be one-dimensional array, e.g. [1,2,3,4,"blabla", ..]
				$arrayParamValues = [];
				foreach ($paramValue as $param) {
					if (is_array($param)) throw new \Exception("You can use only one-dimensional array in where condition");
					$arrayParamValues[] = $this->proccessConditionValue($param);
				}
				$this->condition = $this->replaceString($this->condition, $pos, implode(", ", $arrayParamValues));
			} else {
				$this->condition = $this->replaceString($this->condition, $pos, (string)$this->proccessConditionValue($paramValue));
			}
			$paramsPosition++;
			$placeholdersCount++;
		}
		if ($placeholdersCount !== $parametersCount) {
			throw new \Exception("There are more parameters than placeholders");
		}
	}

	/**
	 * @param mixed $value
	 * Returns escaped string of value for use in condition with (optional) quotes
	 * @return string|int|float
	 */
	private function proccessConditionValue($value)
	{
		// logic value, returned without quotes
		if (is_bool($value)) {
			if ($value === true) return "true";
			return "false";
		}
		// parameter is numeric, input as an integer or float (e.g. '1010000101' is string, but 1010000101 is integer)
		if ((is_numeric($value) && !is_string($value))) {
			return $value;
		}
		// string in condition with single quote ('), value needs to be escaped so it doesn´t break JSON query
		return "'" . $this->escapeConditionString($value) . "'";
	}

	/**
	 * Returns an escaped condition string
	 */
	private function escapeConditionString(string $value): string
	{
		return $value;
	}

	/**
	 * Function for replacing string
	 * in where command, question mark is used as parameter bind
	 * return updated string with $newString
	 */
	private function replaceString(string $targetStr, int $position, string $newString): string
	{
		$rtnString = substr($targetStr, 0, $position);
		$rtnString .= $newString;
		$rtnString .= substr($targetStr, $position + 1, (strlen($targetStr) - $position));
		return $rtnString;
	}

	/**
	 * @return array<string, string>
	 */
	public function getCommand(): array
	{
		return [
			$this->getExpression() => $this->condition,
		];
	}

	public function getExpression(): string
	{
		return self::CLASS_SELECTOR;
	}

}