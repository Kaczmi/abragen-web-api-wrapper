<?php

use AbraApi\Commands\ClassCommand;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

// basic example 
$import = $abraApi->import()
				  ->from("receivedorders/1000000001")
				  ->into("billsofdelivery")
				  ->params("docqueue_id", "1000000001");

Assert::same('billsofdelivery/import/receivedorders/1000000001?select=id', $import->getApiEndpoint());
Assert::equal([
	"params" => [
		"docqueue_id" => "1000000001"
	]
], json_decode($import->getQuery(), true));

$import->params("otherParam", "otherValue");

Assert::equal([
	"params" => [
		"docqueue_id" => "1000000001",
		"otherParam" => "otherValue"
	]
], json_decode($import->getQuery(), true));


// multiple documents into new document
$import = $abraApi->import()
				  ->from("receivedorders")
				  ->into("billsofdelivery")
				  ->inputDocuments([ "1000000001", "2000000001"])
				  ->params([ "docqueue_id" => "1000000001",
							 "otherParam" => "otherValue" ]);

Assert::same('billsofdelivery/import/receivedorders?select=id', $import->getApiEndpoint());

// let´s select new document name and id
$import->select("id", [ "name" => "DisplayName" ]);
Assert::same('billsofdelivery/import/receivedorders?select=id,DisplayName+as+name', $import->getApiEndpoint());

Assert::equal([
	"input_documents" => [
		"1000000001",
		"2000000001"
	],
	"params" => [
		"docqueue_id" => "1000000001",
		"otherParam" => "otherValue"
	]
], json_decode($import->getQuery(), true));

