<?php 

	namespace AbraApi\Callers\Interfaces;

	interface ICaller {

		/**
		 * Must return plain text data in array 
		 * Schema: [
		 * 	"headers" => array(..),
		 *  "content": => string
		 *  "httpcode" => int
		 * ]
		 */
		public function call(string $url, string $body, array $optHeaders = []);

	}