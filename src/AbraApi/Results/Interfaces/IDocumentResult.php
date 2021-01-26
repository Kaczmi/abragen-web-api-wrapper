<?php

namespace AbraApi\Results\Interfaces;

interface IDocumentResult extends IResult
{
	
	public function getContent(): string;
	
}