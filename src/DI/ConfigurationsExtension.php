<?php declare(strict_types = 1);

namespace GrandMedia\Configurations\DI;

use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use GrandMedia\Configurations\ConfigurationsManager;
use GrandMedia\Configurations\JMS\SerializerFactory;
use JMS\Serializer\SerializerInterface;
use Nette\DI\Definitions\Statement;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

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

		$builder->addDefinition($this->prefix('serializer'))
			->setType(SerializerInterface::class)
			->setFactory(
				SerializerFactory::class . '::create',
				[$this->config->cacheDir . '/JMS', $this->config->metadataDirs]
			);
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();

		$xmlDriverName = $builder->getByType(XmlDriver::class);
		if ($xmlDriverName === null) {
			return;
		}

		/** @var \Nette\DI\Definitions\ServiceDefinition $xmlDriver */
		$xmlDriver = $builder->getDefinition($xmlDriverName);

		$xmlDriver->addSetup(
			new Statement('$service->getLocator()->addPaths([?])', [__DIR__ . '/../Doctrine/mapping'])
		);

		/** @var \Nette\DI\Definitions\ServiceDefinition $chainDriver */
		$chainDriver = $builder->getDefinitionByType(MappingDriverChain::class);
		$chainDriver->addSetup('addDriver', [$xmlDriver, 'GrandMedia\Configurations']);
	}

}
