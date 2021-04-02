<?php

use AbraApi\Results\AbraApiGetResult;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$result = new AbraApiGetResult(json_encode([
	[
		"id" => "1000000101"
	]
]), [], 200);

Assert::same("1000000101", $result->fetch()->id);

$result = new AbraApiGetResult(json_encode([
	[
		"id" => "1000000101"
	]
]), [], 200);

Assert::same("1000000101", $result->fetchField("id"));

$result = new AbraApiGetResult(json_encode([
	[
		"id" => "1000000101"
	]
]), [], 200);
Assert::same("1000000101", $result->fetchAll()[0]->id);

$result = new AbraApiGetResult(json_encode([
	[
		"id" => "1000000101"
	],
	[
		"id" => "2000000101"
	],
	[
		"id" => "3000000101"
	],
]), [], 200);
Assert::same([ "1000000101", "2000000101", "3000000101" ], $result->fetchFlat("id"));
