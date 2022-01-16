<?php

use AbraApi\Commands\OrderByCommand;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$orderByCommand = new OrderByCommand("name");
Assert::same(["orderby" => [["value" => "name", "desc" => false]]], $orderByCommand->getCommand());

$orderByCommand = new OrderByCommand("name", "code");
Assert::same(["orderby" => [["value" => "name", "desc" => false], ["value" => "code", "desc" => false]]], $orderByCommand->getCommand());

$orderByCommand = new OrderByCommand("name", "code", ["shortname" => true]);
Assert::same(
	["orderby" => [["value" => "name", "desc" => false], ["value" => "code", "desc" => false], ["value" => "shortname", "desc" => true]]],
	$orderByCommand->getCommand()
);

$orderByCommand = new OrderByCommand(["name"], ["code"], ["shortname" => true]);
Assert::same(
	["orderby" => [["value" => "name", "desc" => false], ["value" => "code", "desc" => false], ["value" => "shortname", "desc" => true]]],
	$orderByCommand->getCommand()
);

$orderByCommand = new OrderByCommand(["name", "code", "shortname" => true]);
Assert::same(
	["orderby" => [["value" => "name", "desc" => false], ["value" => "code", "desc" => false], ["value" => "shortname", "desc" => true]]],
	$orderByCommand->getCommand()
);

Assert::exception(static function() {
	new OrderByCommand(["name", "code", ["shortname" => true]]);
}, \Exception::class, "OrderBy command does not support nested arrays.");

Assert::exception(static function() {
	new OrderByCommand(["name", "code", "shortname" => "desc"]);
}, \Exception::class, "Parameter descending of OrderByCommand must be bool value");

Assert::exception(static function() {
	new OrderByCommand([123]);
}, \Exception::class, "Column of orderBy command is supposed to be string, '123' given");

Assert::exception(static function() {
	new OrderByCommand([new \stdClass()]);
}, \Exception::class, "Column of orderBy command is supposed to be string, instance of stdClass given");

