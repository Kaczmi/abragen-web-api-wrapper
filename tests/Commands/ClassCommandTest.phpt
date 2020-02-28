<?php

use AbraApi\Commands\ClassCommand;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$classCommand = new ClassCommand("storecards");
Assert::same("storecards", $classCommand->getClass());
Assert::same([ "class" => "storecards"], $classCommand->getCommand());

$classCommand2 = new ClassCommand("050I5SAOS3DL3ACU03KIU0CLP4 "); // packed CLSID with whitespace
Assert::same("050I5SAOS3DL3ACU03KIU0CLP4", $classCommand2->getClass());
Assert::same([ "class" => "050I5SAOS3DL3ACU03KIU0CLP4"], $classCommand2->getCommand());

Assert::exception(function() { new ClassCommand(""); }, Exception::class, "BO name expected, empty string given.");