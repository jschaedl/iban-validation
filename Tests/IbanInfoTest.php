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

use Iban\Validation\Iban;
use Iban\Validation\IbanInfo;
use Iban\Validation\Swift\Registry;
use Iban\Validation\Swift\RegistryLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class IbanInfoTest extends TestCase
{
    /**
     * @var IbanInfo
     */
    private $ibanInfo;

    protected function setUp()
    {
        $this->ibanInfo = new IbanInfo(
            new Iban('DE45 5005 0201 1241 5398 70'),
            new Registry(new RegistryLoader(__DIR__ . '/Swift/iban_registry.yaml'))
        );
    }

    /**
     * @expectedException Iban\Validation\Swift\Exception\UnsupportedCountryCodeException
     */
    public function testItShouldThrowUnsupportedCountryCodeException()
    {
        new IbanInfo(
            new Iban('ZZ45 5005 0201 1241 5398 70')
        );
    }

    public function testIbanCountryCreation()
    {
        $expectedData = $this->getData();

        $this->assertEquals($expectedData['DE']['country_name'], $this->ibanInfo->getCountryName());
        $this->assertEquals($expectedData['DE']['iban_structure'], $this->ibanInfo->getIbanStructureSwift());
        $this->assertEquals($expectedData['DE']['bban_structure'], $this->ibanInfo->getBbanStructureSwift());
        $this->assertEquals($expectedData['DE']['iban_regex'], $this->ibanInfo->getIbanRegex());
        $this->assertEquals($expectedData['DE']['bban_regex'], $this->ibanInfo->getBbanRegex());
        $this->assertEquals($expectedData['DE']['iban_length'], $this->ibanInfo->getIbanLength());
        $this->assertEquals($expectedData['DE']['bban_length'], $this->ibanInfo->getBbanLength());
        $this->assertEquals($expectedData['DE']['iban_electronic_format_example'], $this->ibanInfo->getIbanElectronicExample());
        $this->assertEquals($expectedData['DE']['iban_print_format_example'], $this->ibanInfo->getIbanPrintExample());
    }

    /**
     * @return array
     */
    private function getData()
    {
        return Yaml::parseFile(__DIR__ . '/Swift/iban_registry.yaml');
    }
}
