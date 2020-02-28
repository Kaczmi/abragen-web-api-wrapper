<?php

use AbraApi\Commands\ClassCommand;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$body = $abraApi->delete()
		->class("storecards")
		->whereId("10000000001");

Assert::same('storecards/10000000001', $body->getApiEndpoint());

$body = $abraApi->delete()
		->setSource("storecards/10000000001/rows/20000000001");

Assert::same('storecards/10000000001/rows/20000000001', $body->getApiEndpoint());

Assert::exception(function() use($abraApi) {
	$abraApi->delete()
			->class("storecards")
			->setSource("storecards/10000000001/rows/20000000001")
			->whereId("10000000001")
			->getApiEndpoint();
}, \Exception::class, "You can set only one source for delete() query.");

Assert::exception(function() use($abraApi) {
	$abraApi->delete()
			->class("storecards")
			->getApiEndpoint();
}, \Exception::class, "You need to specify ID of row to be deleted using whereId() command");