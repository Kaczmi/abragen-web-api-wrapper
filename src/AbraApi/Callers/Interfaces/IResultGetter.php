<?php 

	namespace AbraApi\Callers\Interfaces;

	interface IResultGetter {
		
		/**
		 * @param string $url
		 * @param string $body
		 * @param array<mixed>  $optHeaders
		 *
		 * @return mixed
		 */
		public function getResult(string $url, string $body, array $optHeaders = []): \AbraApi\Results\Interfaces\IResult;

	}