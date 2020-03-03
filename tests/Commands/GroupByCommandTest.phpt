<?php

use AbraApi\Commands\GroupByCommand;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$groupByCommand = new GroupByCommand("name");
Assert::same([ "groupby" => [ "name" ]], $groupByCommand->getCommand());

$groupByCommand = new GroupByCommand("name", "code");
Assert::same([ "groupby" => [ "name", "code" ]], $groupByCommand->getCommand());

Assert::exception(function() {
	$groupByCommand = new GroupByCommand("name", "code", [ "shortname" => true ]);
	$groupByCommand->getCommand();
}, \Exception::class, "Group by parameter is supposed to be name of column to aggregate.");