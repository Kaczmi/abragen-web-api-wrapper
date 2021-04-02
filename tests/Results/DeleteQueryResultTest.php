<?php

use AbraApi\Results\AbraApiDeleteResult;
use Tester\Assert;

require __DIR__."/../bootstrap.php";

Assert::noError(function() {
	new AbraApiDeleteResult("", [], 204); // 204 => no content
});