<?php

use Tester\Assert;

require __DIR__."/../bootstrap.php";

$document = $abraApi->update()
		->class("storecards")
		->data("name", "test")
		->whereId("1000000001");

Assert::same('{"name":"test"}', $document->getQuery());
Assert::same('storecards/1000000001?select=id', $document->getApiEndpoint());

$document = $abraApi->update()
		->class("storecards")
		->data("name", "test")
		->data("name2", "test2")
		->whereId("1000000001");

Assert::equal(["name" => "test", "name2" => "test2"], (array)(json_decode($document->getQuery())));

$document = $abraApi->update()
		->class("storecards")
		->data("name", "test")
		->data([
			"rows" => [
				[
					"id" => "1000000120",
					"storecard_id" => "1000000001",
				],
			],
		])
		->whereId("1000000001");

Assert::equal(["name" => "test", "rows" => [["id" => "1000000120", "storecard_id" => "1000000001"]]], json_decode($document->getQuery(), true));

$document = $abraApi->update()
		->class("storecards")
		->select(["id", "name", "code"])
		->whereId("1000000001");

Assert::same("storecards/1000000001?select=id,name,code", $document->getApiEndpoint());

$document = $abraApi->update()
		->class("storecards")
		->select(["id", "storecardName" => "name", "code"])
		->whereId("1000000001");

Assert::same("storecards/1000000001?select=id,name+as+storecardName,code", $document->getApiEndpoint());

Assert::exception(static function() use($abraApi) {
	$abraApi->update()
			->whereId("1000000001")
			->getApiEndpoint();
}, \Exception::class, "Update query must specify bussiness object to be edited (class)");

Assert::exception(static function() use($abraApi) {
	$abraApi->update()
			->class("storecards")
			->getApiEndpoint();
}, \Exception::class, "Update query must specify ID of row to be edited.");

Assert::exception(static function() use($abraApi) {
	$abraApi->update()
			->class("storecards")
			->whereId("1000000001")
			->getQuery();
}, \Exception::class, "You need to specify data() - which columns are supposed to be edited in update query");