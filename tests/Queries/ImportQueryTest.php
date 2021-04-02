<?php

use AbraApi\Commands\ClassCommand;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

// basic example 
$import = $abraApi->import()
				  ->from("receivedorders", [ "1000000001" ])
				  ->into("billsofdelivery")
				  ->params("docqueue_id", "1000000001");

Assert::same('billsofdelivery/import/receivedorders?select=id', $import->getApiEndpoint());
Assert::equal([
	"input_documents" => "1000000001",
	"params" => [
		"docqueue_id" => "1000000001"
	]
], json_decode($import->getQuery(), true));

$import->params("otherParam", "otherValue");

Assert::equal([
	"input_documents" => "1000000001",
	"params" => [
		"docqueue_id" => "1000000001",
		"otherParam" => "otherValue"
	]
], json_decode($import->getQuery(), true));


// multiple documents into new document
$import = $abraApi->import()
				  ->select("id", [ "name" => "DisplayName" ]) // let´s select new document name and id
				  ->from("receivedorders", [ "1000000001", "2000000001"])
				  ->into("billsofdelivery")
				  ->params([ "docqueue_id" => "1000000001",
							 "otherParam" => "otherValue" ])
				  ->outputDocumentData("StoreDocQueue_ID", "1000000001");

Assert::same('billsofdelivery/import/receivedorders?select=id,DisplayName+as+name', $import->getApiEndpoint());

Assert::equal([
	"input_documents" => [
		"1000000001",
		"2000000001"
	],
	"params" => [
		"docqueue_id" => "1000000001",
		"otherParam" => "otherValue"
	],
	"output_document_update" => [
		"StoreDocQueue_ID" => "1000000001"
	]
], json_decode($import->getQuery(), true));

// lets add more data to update in new document

$import->outputDocumentData("SomeColumn", "SomeValue");
$import->outputDocumentData([ "SomeColumn2" => "SomeValue2" ], [ "SomeColumn3" => "SomeValue3" ]);

Assert::equal([
	"input_documents" => [
		"1000000001",
		"2000000001"
	],
	"params" => [
		"docqueue_id" => "1000000001",
		"otherParam" => "otherValue"
	],
	"output_document_update" => [
		"StoreDocQueue_ID" => "1000000001",
		"SomeColumn" => "SomeValue",
		"SomeColumn2" => "SomeValue2",
		"SomeColumn3" => "SomeValue3"
	]
], json_decode($import->getQuery(), true));

// import row of some BO into existing row usign CLSID
$import = $abraApi->import()
				  ->from("OBSCO4S1BRD13FY1010DELDFKK", [ "1000000001" ])
				  ->into("OBSCO4S1BRD13FY1010DELDFK2", "9000000001")
				  ->params("docqueue_id", "1000000001");

Assert::same('import?select=id', $import->getApiEndpoint());

Assert::equal([
	"input_document_clsid" => "OBSCO4S1BRD13FY1010DELDFKK",
	"output_document_clsid" => "OBSCO4S1BRD13FY1010DELDFK2",
	"output_document" => "9000000001",
	"input_documents" => "1000000001",
	"params" => [
		"docqueue_id" => "1000000001"
	]
], json_decode($import->getQuery(), true));			  

// and exceptions

Assert::exception(function() use($abraApi) {
	$abraApi->import()
			->into("storecards")
			->execute();
}, \Exception::class, 'You must specify ->from(string $bussinessObject, array $documentIds) and ->into(string $bussinessObject, ?string $documentId) to execute import query.');

Assert::exception(function() use($abraApi) {
	$abraApi->import()
			->from("storecards", [ "1000000001" ])
			->execute();
}, \Exception::class, 'You must specify ->from(string $bussinessObject, array $documentIds) and ->into(string $bussinessObject, ?string $documentId) to execute import query.');

Assert::exception(function() use($abraApi) {
	$abraApi->import()
			->from("storecards", [ "10000000" ])
			->execute();
}, \Exception::class, 'Documents are supposed to be array of Bussiness object ID´s (string with length of 10 characters)');

Assert::exception(function() use($abraApi) {
	$abraApi->import()
			->from("storecards", [ "1000000001" ])
			->into("OBSCO4S1BRD13FY1010DELDFK2")
			->execute();
}, \Exception::class, 'You must specify both left and right importing manager bussiness objects either by ClsID or by API Bussiness object name.');

Assert::exception(function() use($abraApi) {
	$abraApi->import()
			->from("OBSCO4S1BRD13FY1010DELDFK2", [ "1000000001" ])
			->into("storecards")
			->execute();
}, \Exception::class, 'You must specify both left and right importing manager bussiness objects either by ClsID or by API Bussiness object name.');