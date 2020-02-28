<?php

use AbraApi\Results\AbraApiDocumentResult;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

$result = new AbraApiDocumentResult("documentcontent", [], 200);

Assert::same("documentcontent", $result->getContent());