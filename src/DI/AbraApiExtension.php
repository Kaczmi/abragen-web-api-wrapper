<?php declare(strict_types=1);

namespace AbraApi\DI;

use Nette;
use AbraApi;

class AbraApiExtension extends Nette\DI\CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();

		$builder->addDefinition($this->prefix('abraConnection'))
			->setClass(AbraApi\AbraApi::class, [
				"host" => $config["host"],
				"database" => $config["database"],
				"userName" => $config["userName"],
				"password" => $config["password"],
			]);
	}

}