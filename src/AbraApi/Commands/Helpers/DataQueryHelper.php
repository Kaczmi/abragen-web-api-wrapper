<?php declare(strict_types=1);

namespace AbraApi\Commands\Helpers;

class DataQueryHelper
{

	/**
	 * There are two available ways of using data command
	 * You can specify column and new value this way:
	 * ->data("name", "xxx")
	 * which returns in this function one-dimensional array with two values
	 * Or you can specify multiple colums with values in array, where key is column name and value is new value
	 * Array value can also be an array
	 * ->data(["name" => "xxx", "rows" => [ "storecard_id" => "1234567890" ]], [ "firm_id" => "xxxxxxx" ], ...)
	 * @param array<mixed> $dataToProcess
	 * @return array<mixed>
	 */
	public static function processDataCommand(array $dataToProcess): array
	{
		if (\count($dataToProcess) === 2) {
			if (
                isset($dataToProcess[0])
                && isset($dataToProcess[1])
                && !\is_array($dataToProcess[0])
                && !\is_array($dataToProcess[1])
            ) {
				// this is simple data command, only column - value
				return [$dataToProcess[0] => $dataToProcess[1]];
			}
		}
		$command = [];
		foreach ($dataToProcess as $pr) {
			if (!\is_array($pr)) {
				if (\is_object($pr))
					throw new \Exception("Processing data - array was expected, instance of " . \get_class($pr) . " given");
				else
					throw new \Exception("Processing data - array was expected, '" . $pr . "' given");
			}
			$command = \array_merge($command, $pr);
		}
		return $command;
	}

}