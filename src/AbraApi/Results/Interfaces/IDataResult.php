<?php

namespace AbraApi\Results\Interfaces;

interface IDataResult extends IResult
{
	
	public function fetch();
	
	
	public function fetchAll();
	
	
	public function fetchField($field);
	
	
	public function fetchFlat($field);
	
}