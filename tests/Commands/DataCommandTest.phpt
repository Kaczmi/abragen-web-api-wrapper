<?php

use AbraApi\Commands\DataCommand;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$dataCommand = new DataCommand("name", "test");
Assert::same([ "name" => "test" ], $dataCommand->getCommand());

$dataCommand = new DataCommand([ "name" => "test" ]);
Assert::same([ "name" => "test" ], $dataCommand->getCommand());

$dataCommand = new DataCommand([ "name" => "test" ], [ "name2" => "test2" ]);
Assert::same([ "name" => "test", "name2" => "test2" ], $dataCommand->getCommand());

$dataCommand = new DataCommand([ "name" => "test" ], [ "name2" => "test2" ], [ "row" => [ [ "id" => "1000000101", "name" => "test3" ]]]);
Assert::same([ "name" => "test", "name2" => "test2", "row" => [[ "id" => "1000000101", "name" => "test3" ]] ], $dataCommand->getCommand());

Assert::exception(function() {
	new DataCommand("name", "test", "badParameter");
}, \Exception::class, "Data command parameter - array was expected, 'name' given");

Assert::exception(function() {
	new DataCommand(new DataCommand("name", "test"));
}, \Exception::class, "Data command parameter - array was expected, instance of AbraApi\Commands\DataCommand given");