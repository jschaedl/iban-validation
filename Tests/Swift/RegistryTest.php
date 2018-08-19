<?php

/*
 * This file is part of the iban-validation library.
 *
 * (c) Jan Schädlich <mail@janschaedlich.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Iban\Validation\Tests\Swift;

use Iban\Validation\Swift\Registry;
use Iban\Validation\Swift\RegistryLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class RegistryTest extends TestCase
{
    /**
     * @var Registry
     */
    private $registry;

    protected function setUp()
    {
        $this->registry = new Registry(new RegistryLoader(__DIR__ . '/iban_registry.yaml'));
    }

    /**
     * @expectedException Iban\Validation\Swift\Exception\UnsupportedCountryCodeException
     */
    public function testItShouldThrowExceptionForUnsupportedCountryCode()
    {
        $this->registry->getIbanRegex('AA');
    }

    public function testItShouldGiveCorrectValuesForCountryCode()
    {
        $expectedData = $this->getData();

        $this->assertEquals($expectedData['DE']['country_name'], $this->registry->getCountryName('DE'));
        $this->assertEquals($expectedData['DE']['iban_structure'], $this->registry->getIbanStructure('DE'));
        $this->assertEquals($expectedData['DE']['bban_structure'], $this->registry->getBbanStructure('DE'));
        $this->assertEquals($expectedData['DE']['iban_regex'], $this->registry->getIbanRegex('DE'));
        $this->assertEquals($expectedData['DE']['bban_regex'], $this->registry->getBbanRegex('DE'));
        $this->assertEquals($expectedData['DE']['iban_length'], $this->registry->getIbanLength('DE'));
        $this->assertEquals($expectedData['DE']['bban_length'], $this->registry->getBbanLength('DE'));
        $this->assertEquals($expectedData['DE']['iban_electronic_format_example'], $this->registry->getIbanElectronicFormatExample('DE'));
        $this->assertEquals($expectedData['DE']['iban_print_format_example'], $this->registry->getIbanPrintFormatExample('DE'));
    }

    /**
     * @return array
     */
    private function getData()
    {
        return Yaml::parseFile(__DIR__ . '/iban_registry.yaml');
    }
}
