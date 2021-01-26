<?php

namespace AbraApi\Results\Interfaces;

/**
 * this is general class for updating data in abra - delete, update, insert
 */
interface IUpdateResult extends IResult
{
	
	public function getUpdatedId(): string;
	
	
	public function getResult(): \stdClass;
	
}