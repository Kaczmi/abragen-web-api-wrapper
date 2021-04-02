<?php

use AbraApi\Results\AbraApiUpdateResult;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$result = new AbraApiUpdateResult(json_encode([ "id" => "1000000101" ]), [], 200);

Assert::same("1000000101", $result->getUpdatedId());

$result = new AbraApiUpdateResult(json_encode([ "id" => "1000000101", "name" => "exampleName" ]), [], 200);

Assert::same([ "id" => "1000000101", "name" => "exampleName" ], (array)$result->getResult());