<?php

use Tester\Assert;

require __DIR__."/../bootstrap.php";

$document = $abraApi->getDocument()
		->class("storecards")
		->whereId("1000000001")
		->report("1000000001");

Assert::same('query?report=1000000001', $document->getApiEndpoint());
Assert::same('{"class":"storecards","select":["id"],"where":"id in (\'1000000001\')"}', $document->getQuery());

$document = $abraApi->getDocument()
		->class("storecards")
		->whereId(["1000000001", "2000000001"])
		->export("1000000001")
		->b2b();

Assert::same('query?export=1000000001&b2b=true', $document->getApiEndpoint());
Assert::same('{"class":"storecards","select":["id"],"where":"id in (\'1000000001\', \'2000000001\')"}', $document->getQuery());

Assert::exception(static function() use($abraApi) {
	$document = $abraApi->getDocument()
				->whereId("1000000001")
				->report("1000000001")
				->execute();
}, \Exception::class, "To get an export or report, you need to specify class(), whereId() and report() or export()");

Assert::exception(static function() use($abraApi) {
	$document = $abraApi->getDocument()
				->class("storecards")
				->report("1000000001")
				->execute();
}, \Exception::class, "To get an export or report, you need to specify class(), whereId() and report() or export()");

Assert::exception(static function() use($abraApi) {
	$document = $abraApi->getDocument()
				->class("storecards")
				->whereId("1000000001")
				->execute();
}, \Exception::class, "To get an export or report, you need to specify class(), whereId() and report() or export()");

Assert::exception(static function() use($abraApi) {
	$document = $abraApi->getDocument()
				->class("storecards")
				->whereId("1000000001")
				->report("1000000001")
				->export("1000000001")
				->execute();
}, \Exception::class, "You need to specify only one method - report() or export()");