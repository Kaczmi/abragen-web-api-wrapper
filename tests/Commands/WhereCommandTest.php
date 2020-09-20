<?php

use AbraApi\Commands\WhereCommand;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$whereCommand = new WhereCommand("id = '1010101010'");
Assert::same([ "where" => "id = '1010101010'" ], $whereCommand->getCommand());

$whereCommand = new WhereCommand('id = "1010101010"');
Assert::same([ "where" => "id = '1010101010'" ], $whereCommand->getCommand());

$whereCommand = new WhereCommand("id = ? and hidden = ?", '1010000010', false);
Assert::same([ "where" => "id = '1010000010' and hidden = false" ], $whereCommand->getCommand());

$whereCommand = new WhereCommand("id in (?)", [ "1010000010", "2010000010" ]);
Assert::same([ "where" => "id in ('1010000010', '2010000010')" ], $whereCommand->getCommand());

Assert::exception(function() {
	$whereCommand = new WhereCommand("id = ? and hidden = ?", '1010000010');
}, \Exception::class, "There is a missing parameter in condition");

Assert::exception(function() {
	$whereCommand = new WhereCommand("id = ? and hidden = ?", '1010000010', false, "parameter");
}, \Exception::class, "There are more parameters than placeholders");

Assert::exception(function() {
	$whereCommand = new WhereCommand("id = ? and hidden = ?", [ '1010000010', [ '2010000010' ]], false);
}, \Exception::class, "You can use only one-dimensional array in where condition");