<?php

/*
 * This file is part of the iban-validation library.
 *
 * (c) Jan Schädlich <mail@janschaedlich.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Iban\Validation\Tests;

use Iban\Validation\CountryInfo;
use Iban\Validation\Iban;
use Iban\Validation\IbanInfo;
use Iban\Validation\Swift\Registry;
use Iban\Validation\Swift\RegistryLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class CountryInfoTest extends TestCase
{
    /**
     * @var CountryInfo
     */
    private $countryInfo;

    protected function setUp()
    {
        $this->countryInfo = new CountryInfo(
            'DE',
            new Registry(new RegistryLoader(__DIR__ . '/Swift/iban_registry.yaml'))
        );
    }

    /**
     * @expectedException Iban\Validation\Swift\Exception\UnsupportedCountryCodeException
     */
    public function testItShouldThrowUnsupportedCountryCodeException()
    {
        new CountryInfo('ZZ');
    }

    public function testIbanCountryCreation()
    {
        $expectedData = $this->getData();

        $this->assertEquals($expectedData['DE']['country_name'], $this->countryInfo->getCountryName());
        $this->assertEquals($expectedData['DE']['iban_structure'], $this->countryInfo->getIbanStructureSwift());
        $this->assertEquals($expectedData['DE']['bban_structure'], $this->countryInfo->getBbanStructureSwift());
        $this->assertEquals($expectedData['DE']['iban_regex'], $this->countryInfo->getIbanRegex());
        $this->assertEquals($expectedData['DE']['bban_regex'], $this->countryInfo->getBbanRegex());
        $this->assertEquals($expectedData['DE']['iban_length'], $this->countryInfo->getIbanLength());
        $this->assertEquals($expectedData['DE']['bban_length'], $this->countryInfo->getBbanLength());
        $this->assertEquals($expectedData['DE']['iban_electronic_format_example'], $this->countryInfo->getIbanElectronicExample());
        $this->assertEquals($expectedData['DE']['iban_print_format_example'], $this->countryInfo->getIbanPrintExample());
    }

    /**
     * @return array
     */
    private function getData()
    {
        return Yaml::parseFile(__DIR__ . '/Swift/iban_registry.yaml');
    }
}
