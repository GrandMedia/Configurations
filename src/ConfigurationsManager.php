<?php declare(strict_types = 1);

namespace GrandMedia\Configurations;

use Doctrine\ORM\EntityManagerInterface;
use GrandMedia\Configurations\Exceptions\ConfigurationNotFound;
use JMS\Serializer\SerializerInterface;

final class ConfigurationsManager
{

	private const SERIALIZER_FORMAT = 'json';

	private string $module;
	private EntityManagerInterface $em;
	private SerializerInterface $serializer;

	public function __construct(string $module, EntityManagerInterface $em, SerializerInterface $serializer)
	{
		$this->module = $module;
		$this->em = $em;
		$this->serializer = $serializer;
	}

	/**
	 * @param string $name
	 * @param mixed $data
	 */
	public function save(string $name, $data): void
	{
		$stringData = $this->serializer->serialize($data, self::SERIALIZER_FORMAT);

		$configuration = $this->find($name);
		if ($configuration === null) {
			$this->em->persist(Configuration::fromValues($this->module, $name, $stringData));
		} else {
			$configuration->changeData($stringData);
		}

		$this->em->transactional(
			static function (): void {
			}
		);
	}

	/**
	 * @return mixed
	 * @template T
	 * @psalm-param class-string<T> $type
	 * @psalm-return T
	 *
	 * @throws \GrandMedia\Configurations\Exceptions\ConfigurationNotFound
	 */
	public function get(string $name, string $type)
	{
		$configuration = $this->find($name);
		if ($configuration === null) {
			throw ConfigurationNotFound::from($this->module, $name);
		}

		return $this->serializer->deserialize($configuration->getData(), $type, self::SERIALIZER_FORMAT);
	}

	private function find(string $name): ?Configuration
	{
		return $this->em->find(
			Configuration::class,
			[
				'module' => $this->module,
				'name' => $name,
			]
		);
	}

}
