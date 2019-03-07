<?php declare(strict_types = 1);

namespace GrandMedia\Configurations\JMS;

use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

final class SerializerFactory
{

	/**
	 * @param string[] $metadataDirs
	 */
	public static function create(string $cacheDir, array $metadataDirs): Serializer
	{
		return SerializerBuilder::create()
			->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy())
			->setCacheDir($cacheDir)
			->setSerializationContextFactory(
				function (): SerializationContext {
					$context = new SerializationContext();
					$context->setSerializeNull(true);

					return $context;
				}
			)
			->setMetadataDirs($metadataDirs)
			->build();
	}

}
