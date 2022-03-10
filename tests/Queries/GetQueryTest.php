<?php

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

Assert::same(
	'{"class":"issuedinvoices","select":[{"name":"radky_faktury","value":{"field":"rows","query":{"select":["storecard_id"]}}}],"where":"id = \'1000000101\'"}',
	$body
);

$body = $abraApi->get()
		->class("issuedinvoices")
		->select(["invoice_name" => "DisplayName"])
		->whereId(["1000000101", "2000000101"])
		->getQuery();

Assert::same(
	'{"class":"issuedinvoices","select":[{"name":"invoice_name","value":"DisplayName"}],"where":"id in (\'1000000101\', \'2000000101\')"}',
	$body
);

$body = $abraApi->get()
		->class("issuedinvoices")
		->select(["invoice_name" => "DisplayName"])
		->whereId("1000000101")
		->getQuery();

Assert::same('{"class":"issuedinvoices","select":[{"name":"invoice_name","value":"DisplayName"}],"where":"id in (\'1000000101\')"}', $body);

$body = $abraApi->get()
		->class("issuedinvoices")
		->select(["invoice_name" => "DisplayName"])
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
			"value" => "DisplayName",
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
										"x_column",
									],
								],
							],
						],
					],
					"orderby" => [
						[
							"value" => "amount",
							"desc" => true,
						],
					],
				],
			],
		],
	],
	"where" => "id in ('1000000101')",
], json_decode($body, true));


$body = $abraApi->get()
		->class("issuedinvoices")
		->select("id")
		->where("name LIKE ? and code LIKE ?", "*test*", "*TEST*")
		->limit(5)
		->skip(10)
		->orderBy(['id' => FALSE])
		->getQuery();

Assert::equal([
	"class" => "issuedinvoices",
	"select" => [
		"id",
	],
	"where" => "name LIKE '*test*' and code LIKE '*TEST*'",
	"take" => 5,
	"skip" => 10,
	"orderby" => [
		[
			'value' => 'id',
			'desc' => FALSE,
		],
	],
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
					"id",
				],
				"where" => "vazba_id = :id",
				"take" => 10,
			],
		],
	],
], json_decode($body, true));

$body = $abraApi->get()
	  ->class("issuedinvoices")
	  ->select("displayname")
	  ->expand("issuedInvoiceRows", "rows")
		->select(["productName" => "storecard_id.name"])
		->end()
	  ->whereId("1010000010")
	  ->getQuery();

Assert::equal([
	"class" => "issuedinvoices",
	"select" => [
		"displayname",
		[
			"name" => "issuedInvoiceRows",
			"value" => [
				"field" => "rows",
				"query" => [
					"select" => [
						[
							"name" => "productName",
							"value" => "storecard_id.name",
						],
					],
				],
			],
		],
	],
	"where" => "id in ('1010000010')",
], json_decode($body, true));

$body = $abraApi->get()
		->class("issuedinvoices")
		->select("id")
		->expand("exampleName", "x_vazby") // "x_vazby" should be ignored, because we use subquery into other table
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
			"name" => "exampleName",
			"value" => [
				"class" => "relations",
				"select" => [
					"id",
				],
				"where" => "vazba_id = :id",
				"take" => 10,
			],
		],
	],
], json_decode($body, true));

$body = $abraApi->get()
		->class("issuedinvoices")
		->fulltext("FVT-1/2020")
		->select("id")
		->getQuery();

Assert::equal([
	"class" => "issuedinvoices",
	"fulltext" => "FVT-1/2020",
	"select" => [
		"id",
	],
], json_decode($body, true));

$body = $abraApi->get()
		->class("issuedinvoices")
		->fulltext("FVT-1/2020")
		->select("id")
		->expand("rows")
			->select(["storecardName" => "storecard_id.name"])
			->fulltext("test")
			->end()
		->getQuery();

Assert::equal([
	"class" => "issuedinvoices",
	"fulltext" => "FVT-1/2020",
	"select" => [
		"id",
		[
			"name" => "rows",
			"value" => [
				"field" => "rows",
				"query" => [
					"fulltext" => "test",
					"select" => [
						[
							"name" => "storecardName",
							"value" => "storecard_id.name",
						],
					],
				],
			],
		],
	],
], json_decode($body, true));

$body = $abraApi->get()
		->class("issuedinvoices")
		->fulltext("FVT-1/2020")
		->select("id")
		->expand("rows")
			->select(["storecardName" => "storecard_id.name"])
			->end()
		->expand("firm_id")
			->select(["firm_name" => "name"])
			->end()
		->getQuery();

Assert::equal([
	"class" => "issuedinvoices",
	"fulltext" => "FVT-1/2020",
	"select" => [
		"id",
		[
			"name" => "rows",
			"value" => [
				"field" => "rows",
				"query" => [
					"select" => [
						[
							"name" => "storecardName",
							"value" => "storecard_id.name",
						],
					],
				],
			],
		],
		[
			"name" => "firm_id",
			"value" => [
				"field" => "firm_id",
				"query" => [
					"select" => [
						[
							"name" => "firm_name",
							"value" => "name",
						],
					],
				],
			],
		],
	],
], json_decode($body, true));

