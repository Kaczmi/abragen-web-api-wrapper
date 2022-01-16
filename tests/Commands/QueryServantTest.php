<?php

use AbraApi\Commands\ClassCommand;
use AbraApi\CommandBuilders\QueryServant;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

Assert::exception(static function() {
	$queryServant = new QueryServant;
	$queryServant->whereId("nonsense");
}, \Exception::class, "ID of row expected, 'nonsense' given.");

Assert::exception(static function() {
	$queryServant = new QueryServant;
	$queryServant->class("test")->class("test");
}, \Exception::class, "Cannot add two same queries of AbraApi\Commands\ClassCommand");

$queryServant = new QueryServant;
$queryServant->class("test");
Assert::true($queryServant->hasCommand(ClassCommand::class));

Assert::noError(static function() {
	$queryServant = new QueryServant;
	$queryServant->data("data1key", "data1value")->data(["data2key" => "data2value", "data3key" => "data3value"]);
});