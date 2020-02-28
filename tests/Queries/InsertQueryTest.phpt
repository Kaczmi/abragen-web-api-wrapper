<?php

use AbraApi\Commands\ClassCommand;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$document = $abraApi->insert()
		->class("storecards")
		->data("name", "test");

Assert::same('{"name":"test"}', $document->getQuery());
Assert::same('storecards?select=id', $document->getApiEndpoint());

$document = $abraApi->insert()
		->class("storecards")
		->data("name", "test")
		->data("name2", "test2");

Assert::equal([ "name" => "test", "name2" => "test2" ], (array)(json_decode($document->getQuery())));

$document = $abraApi->insert()
		->class("storecards")
		->data("name", "test")
		->data([
			"rows" => [
				[
					"id" => "1000000120",
					"storecard_id" => "1000000001"
				]
			]
		]);

Assert::equal([ "name" => "test", "rows" => [[ "id" => "1000000120", "storecard_id" => "1000000001" ]]], (json_decode($document->getQuery(), true)));

$document = $abraApi->insert()
		->class("storecards")
		->select(["id", "name", "code"]);

Assert::same("storecards?select=id,name,code", $document->getApiEndpoint());

$document = $abraApi->insert()
		->class("storecards")
		->select(["id", "storecardName" => "name", "code"]);

Assert::same("storecards?select=id,name+as+storecardName,code", $document->getApiEndpoint());

Assert::exception(function() use($abraApi) {
	$abraApi->insert()
			->data("name", "test")
			->getApiEndpoint();
}, \Exception::class, "Insert query must specify bussiness object (class)");

Assert::exception(function() use($abraApi) {
	$abraApi->insert()
			->class("storecards")
			->getQuery();
}, \Exception::class, "You need to specify data() to create new Abra record.");