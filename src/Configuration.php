<?php declare(strict_types = 1);

namespace GrandMedia\Configurations;

use Assert\Assertion;

final class Configuration
{

	/**
	 * @var string
	 */
	private $module;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var mixed[]
	 */
	private $data;

	private function __construct()
	{
	}

	/**
	 * @param mixed[] $data
	 */
	public static function fromValues(string $module, string $name, array $data): self
	{
		Assertion::maxLength($module, 32);
		Assertion::maxLength($name, 32);

		$configuration = new self();
		$configuration->module = $module;
		$configuration->name = $name;
		$configuration->data = $data;

		return $configuration;
	}

	/**
	 * @param mixed[] $data
	 */
	public function changeData(array $data): void
	{
		$this->data = $data;
	}

	public function getModule(): string
	{
		return $this->module;
	}

	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return mixed[]
	 */
	public function getData(): array
	{
		return $this->data;
	}

}
