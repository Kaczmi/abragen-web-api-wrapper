<?php

use AbraApi\Results\AbraApiImportResult;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$result = new AbraApiImportResult(json_encode([ "id" => "100000101" ]), [], 201);

Assert::same("100000101", $result->getId());