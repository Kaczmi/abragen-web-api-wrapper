<?php

	namespace AbraApi\Results\Interfaces;

	interface IDataResult {

		public function fetch();
		public function fetchAll();
		public function fetchField($field);
		public function fetchFlat($field);

	}