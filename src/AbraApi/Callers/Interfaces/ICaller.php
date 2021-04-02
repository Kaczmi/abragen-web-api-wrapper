<?php

namespace AbraApi\Callers\Interfaces;

interface ICaller
{

	/**
	 * Must return plain text data in array
	 * Schema: [
	 *    "headers" => array(..),
	 *  "content": => string
	 *  "httpcode" => int
	 * ]
	 * @param string $url
	 * @param string $body
	 * @param array<mixed> $optHeaders
	 * @return array<mixed>
	 */
	public function call(string $url, string $body, array $optHeaders = []): array;

}