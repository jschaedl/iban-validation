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
use Iban\Validation\Swift\Exception\UnsupportedCountryCodeException;
use PHPUnit\Framework\TestCase;

final class CountryInfoTest extends TestCase
{
    public function test_it_should_throw_UnsupportedCountryCodeException_for_not_supported_country(): void
    {
        $this->expectException(UnsupportedCountryCodeException::class);
        new CountryInfo('ZZ');
    }

    public function test_iban_country_creation()
    {
        $expectedData = include dirname(__DIR__, 1).'/Resource/iban_registry_202205r92.php';

        $countryInfo = new CountryInfo('DE');

        self::assertEquals($expectedData['DE']['country_name'], $countryInfo->getCountryName());
        self::assertEquals($expectedData['DE']['iban_structure'], $countryInfo->getIbanStructureSwift());
        self::assertEquals($expectedData['DE']['bban_structure'], $countryInfo->getBbanStructureSwift());
        self::assertEquals($expectedData['DE']['iban_regex'], $countryInfo->getIbanRegex());
        self::assertEquals($expectedData['DE']['bban_regex'], $countryInfo->getBbanRegex());
        self::assertEquals($expectedData['DE']['iban_length'], $countryInfo->getIbanLength());
        self::assertEquals($expectedData['DE']['bban_length'], $countryInfo->getBbanLength());
        self::assertEquals($expectedData['DE']['iban_electronic_format_example'], $countryInfo->getIbanElectronicExample());
        self::assertEquals($expectedData['DE']['iban_print_format_example'], $countryInfo->getIbanPrintExample());
    }
}
