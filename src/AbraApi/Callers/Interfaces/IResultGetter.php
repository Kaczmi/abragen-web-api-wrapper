<?php 

	namespace AbraApi\Callers\Interfaces;

	interface IResultGetter {

		public function getResult($url, $body, $optHeaders = array());

	}