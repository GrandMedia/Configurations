<?php declare(strict_types = 1);

namespace GrandMedia\Configurations\DI;

use GrandMedia\Configurations\ConfigurationsManager;
use GrandMedia\Configurations\JMS\SerializerFactory;
use JMS\Serializer\SerializerInterface;
use Nette\DI\Helpers;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nettrine\ORM\DI\Helpers\MappingHelper;

/**
 * @property-read \stdClass $config
 */
final class ConfigurationsExtension extends \Nette\DI\CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure(
			[
				'cacheDir' => Expect::string('%tempDir%/cache/GrandMedia.Configurations'),
				'metadataDirs' => Expect::array(),
			]
		);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('manager'))
			->setType(ConfigurationsManager::class);

		$cacheDir = Helpers::expand($this->config->cacheDir . '/JMS', $builder->parameters);
		$metadataDirs = \array_map(
			fn(string $path): string => Helpers::expand($path, $builder->parameters),
			$this->config->metadataDirs
		);
		$builder->addDefinition($this->prefix('serializer'))
			->setType(SerializerInterface::class)
			->setFactory(
				SerializerFactory::class . '::create',
				[$cacheDir, $metadataDirs]
			);
	}

	public function beforeCompile(): void
	{
		MappingHelper::of($this)
			->addXml('GrandMedia\Configurations', __DIR__ . '/../Doctrine/mapping', true);
	}

}
