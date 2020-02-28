<?php

use AbraApi\Results\AbraApiInsertResult;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$result = new AbraApiInsertResult(json_encode([ "id" => "100000101" ]), [], 200);

Assert::same("100000101", $result->getInsertedId());