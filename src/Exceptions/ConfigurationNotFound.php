<?php declare(strict_types = 1);

namespace GrandMedia\Configurations\Exceptions;

final class ConfigurationNotFound extends \LogicException
{

	public static function from(string $name): self
	{
		return new self(\sprintf('Configuration "%s" not found.', $name));
	}

}
