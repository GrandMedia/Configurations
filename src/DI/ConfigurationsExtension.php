<?php declare(strict_types = 1);

namespace GrandMedia\Configurations\DI;

use GrandMedia\Configurations\ConfigurationsManagerFactory;
use GrandMedia\Configurations\JMS\SerializerFactory;
use JMS\Serializer\Serializer;
use Nette\DI\Helpers;

final class ConfigurationsExtension extends \Nette\DI\CompilerExtension
{

	/**
	 * @var mixed[]
	 */
	private $defaults = [
		'cacheDir' => '%tempDir%/cache/GrandMedia.Configurations',
		'metadataDirs' => [],
	];

	public function loadConfiguration(): void
	{
		$containerBuilder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);
		$config = Helpers::expand($config, $containerBuilder->parameters);

		$containerBuilder->addDefinition($this->prefix('managerFactory'))
			->setImplement(ConfigurationsManagerFactory::class);

		$containerBuilder->addDefinition($this->prefix('serializer'))
			->setType(Serializer::class)
			->setFactory(
				SerializerFactory::class . '::create',
				[$config['cacheDir'] . '/JMS', $config['metadataDirs']]
			);
	}

}
