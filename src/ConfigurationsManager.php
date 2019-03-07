<?php declare(strict_types = 1);

namespace GrandMedia\Configurations;

use Doctrine\ORM\EntityManagerInterface;
use GrandMedia\Configurations\Exceptions\ConfigurationNotFound;
use JMS\Serializer\Serializer;

final class ConfigurationsManager
{

	/**
	 * @var string
	 */
	private $module;

	/**
	 * @var \Doctrine\ORM\EntityManagerInterface
	 */
	private $em;

	/**
	 * @var \JMS\Serializer\Serializer
	 */
	private $serializer;

	public function __construct(string $module, EntityManagerInterface $em, Serializer $serializer)
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
		$arrayData = $this->serializer->toArray($data);

		$configuration = $this->find($name);
		if ($configuration === null) {
			$this->em->persist(Configuration::fromValues($this->module, $name, $arrayData));
		} else {
			$configuration->changeData($arrayData);
		}

		$this->em->transactional(
			function (): void {
			}
		);
	}

	/**
	 * @return mixed
	 * @throws \GrandMedia\Configurations\Exceptions\ConfigurationNotFound
	 */
	public function get(string $name, string $dataClass)
	{
		$configuration = $this->find($name);
		if ($configuration === null) {
			throw ConfigurationNotFound::from($this->module, $name);
		}

		return $this->serializer->fromArray($configuration->getData(), $dataClass);
	}

	private function find(string $name): ?Configuration
	{
		$configuration = $this->em->getRepository(Configuration::class)->findOneBy(
			[
				'module' => $this->module,
				'name' => $name,
			]
		);

		return $configuration instanceof Configuration ? $configuration : null;
	}

}
