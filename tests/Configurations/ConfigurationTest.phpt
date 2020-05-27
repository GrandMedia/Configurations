<?php declare(strict_types = 1);

namespace GrandMediaTests\Configurations;

use GrandMedia\Configurations\Configuration;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class ConfigurationTest extends \Tester\TestCase
{

	public function testFromValues(): void
	{
		Configuration::fromValues('name', 'value');

		Assert::true(true);
	}

}

(new ConfigurationTest())->run();
