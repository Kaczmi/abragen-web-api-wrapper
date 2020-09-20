<?php

namespace AbraApi\Results\Interfaces;

interface IInsertResult extends IResult
{
	
	public function getInsertedId();
	
}