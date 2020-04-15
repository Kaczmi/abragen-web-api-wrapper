<?php

	namespace AbraApi\Results\Interfaces;

	interface IImportResult {

		public function getId(): string;
		public function getResult();
		
	}