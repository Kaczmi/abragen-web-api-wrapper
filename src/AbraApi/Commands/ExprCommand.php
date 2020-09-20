<?php 

	declare(strict_types = 1);

	namespace AbraApi\Commands;

	class ExprCommand extends WhereCommand implements Interfaces\ICommandQueryBuilder {

		public function getExpression(): string {
			return "expr";
		}

	}