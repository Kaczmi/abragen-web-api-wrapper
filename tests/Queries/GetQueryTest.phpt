<?php

use AbraApi\Commands\ClassCommand;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$body = $abraApi->get()
		->class("storecards")
		->select("id")
		->where("name LIKE '*test*'")
		->getQuery();

Assert::same('{"class":"storecards","select":["id"],"where":"name LIKE \'*test*\'"}', $body);

$body = $abraApi->get()
		->class("issuedinvoices")
		->expand("radky_faktury", "rows")
			->select("storecard_id")
			->end()
		->where("id = ?", '1000000101')
		->getQuery();

Assert::same('{"class":"issuedinvoices","select":[{"name":"radky_faktury","value":{"field":"rows","query":{"select":["storecard_id"]}}}],"where":"id = \'1000000101\'"}', $body);

$body = $abraApi->get()
		->class("issuedinvoices")
		->select([ "invoice_name" => "DisplayName" ])
		->whereId([ "1000000101", "2000000101" ])
		->getQuery();

Assert::same('{"class":"issuedinvoices","select":[{"name":"invoice_name","value":"DisplayName"}],"where":"id in (\'1000000101\', \'2000000101\')"}', $body);

$body = $abraApi->get()
		->class("issuedinvoices")
		->select([ "invoice_name" => "DisplayName" ])
		->whereId("1000000101")
		->getQuery();

Assert::same('{"class":"issuedinvoices","select":[{"name":"invoice_name","value":"DisplayName"}],"where":"id in (\'1000000101\')"}', $body);

$body = $abraApi->get()
		->class("issuedinvoices")
		->select([ "invoice_name" => "DisplayName" ])
		->expand("rows")
			->select("storecard_id")
			->expand("skladova_karta", "storecard_id")
				->select("name", "code", "x_column")
				->end()
			->orderBy(["amount" => true])
			->end()
		->whereId("1000000101")
		->getQuery();

Assert::equal([
	"class" => "issuedinvoices",
	"select" => [
		[
			"name" => "invoice_name",
			"value" => "DisplayName"
		],
		[
			"name" => "rows",
			"value" => [
				"field" => "rows",
				"query" => [
					"select" => [
						"storecard_id",
						[
							"name" => "skladova_karta",
							"value" => [
								"field" => "storecard_id",
								"query" => [
									"select" => [
										"name",
										"code",
										"x_column"
									]
								]
							]
						]
					],
					"orderby" => [
						[
							"value" => "amount",
							"desc" => true
						]
					]
				]
			]
		]
	],
	"where" => "id in ('1000000101')"
], json_decode($body, true));


$body = $abraApi->get()
		->class("issuedinvoices")
		->select("id")
		->where("name LIKE ? and code LIKE ?", "*test*", "*TEST*")
		->limit(5)
		->skip(10)
		->getQuery();

Assert::equal([
	"class" => "issuedinvoices",
	"select" => [
		"id"
	],
	"where" => "name LIKE '*test*' and code LIKE '*TEST*'",
	"take" => 5,
	"skip" => 10
], json_decode($body, true));

$body = $abraApi->get()
		->class("issuedinvoices")
		->select("id")
		->expand("x_vazby")
			->class("relations")
			->select("id")
			->where("vazba_id = :id")
			->limit(10)
			->end()
		->getQuery();

Assert::equal([
	"class" => "issuedinvoices",
	"select" => [
		"id",
		[
			"name" => "x_vazby",
			"value" => [
				"class" => "relations",
				"select" => [
					"id"
				],
				"where" => "vazba_id = :id",
				"take" => 10
			]
		]
	],
], json_decode($body, true));
