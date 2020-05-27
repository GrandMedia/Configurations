<?php declare(strict_types = 1);

namespace GrandMedia\Configurations;

use Assert\Assertion;

class Configuration
{

	public const MAX_NAME_LENGTH = 32;

	private string $name;
	private string $data;

	private function __construct()
	{
	}

	public static function fromValues(string $name, string $data): self
	{
		Assertion::maxLength($name, self::MAX_NAME_LENGTH);

		$configuration = new self();
		$configuration->name = $name;
		$configuration->data = $data;

		return $configuration;
	}

	public function changeData(string $data): void
	{
		$this->data = $data;
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
