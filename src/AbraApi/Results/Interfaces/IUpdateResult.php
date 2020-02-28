<?php

	namespace AbraApi\Results\Interfaces;

	/**
	 * this is general class for updating data in abra - delete, update, insert
	 */
	interface IUpdateResult {

		public function getUpdatedId();
		public function getResult();
		
	}