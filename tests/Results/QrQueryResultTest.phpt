<?php

use AbraApi\Results\AbraApiQrFunctionResult;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$result = new AbraApiQrFunctionResult(json_encode([ "result" => "example output" ]), [], 200);

Assert::same("example output", $result->getResult());