<?php declare(strict_types = 1);

namespace GrandMedia\Configurations;

use Assert\Assertion;

class Configuration
{

	public const MAX_MODULE_LENGTH = 32;
	public const MAX_NAME_LENGTH = 32;

	private string $module;
	private string $name;
	private string $data;

	private function __construct()
	{
	}

	public static function fromValues(string $module, string $name, string $data): self
	{
		Assertion::maxLength($module, self::MAX_MODULE_LENGTH);
		Assertion::maxLength($name, self::MAX_NAME_LENGTH);

		$configuration = new self();
		$configuration->module = $module;
		$configuration->name = $name;
		$configuration->data = $data;

		return $configuration;
	}

	public function changeData(string $data): void
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

	public function getData(): string
	{
		return $this->data;
	}

}
