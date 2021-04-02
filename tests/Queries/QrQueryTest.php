<?php

use AbraApi\Commands\ClassCommand;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$document = $abraApi->qr()
		->expr("NxSQLSelect('SELECT * FROM STORECARDS WHERE ID = ?')", '1000000001');

Assert::same('{"expr":"NxSQLSelect(\'SELECT * FROM STORECARDS WHERE ID = \'1000000001\'\')"}', $document->getQuery());

$document = $abraApi->qr()
		->expr('NxSQLSelect("SELECT * FROM STORECARDS WHERE ID = ?")', '1000000001');

Assert::same('{"expr":"NxSQLSelect(\'SELECT * FROM STORECARDS WHERE ID = \'1000000001\'\')"}', $document->getQuery());


Assert::exception(function() use($abraApi) {
	$abraApi->qr()->getQuery();
}, \Exception::class, 'You must specify QR function using ->expr($expression, ...$parameters) command.');