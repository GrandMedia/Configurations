<?php declare(strict_types = 1);

namespace GrandMedia\Configurations;

interface ConfigurationsManagerFactory
{

	public function create(string $module): ConfigurationsManager;

}
