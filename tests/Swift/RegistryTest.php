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

use Iban\Validation\Swift\Exception\UnsupportedCountryCodeException;
use Iban\Validation\Swift\Registry;
use PHPUnit\Framework\TestCase;

final class RegistryTest extends TestCase
{
    public function test_it_should_throw__unsupported_country_code_exception_for_unsupported_country_code(): void
    {
        $this->expectException(UnsupportedCountryCodeException::class);

        (new Registry())->getIbanRegex('AA');
    }

    public function test_it_should_give_correct_values_for_country_code(): void
    {
        $expectedData = require dirname(__DIR__, 2).'/Resource/iban_registry_202205r92.php';

        $registry = new Registry();

        self::assertEquals($expectedData['DE']['country_name'], $registry->getCountryName('DE'));
        self::assertEquals($expectedData['DE']['iban_structure'], $registry->getIbanStructure('DE'));
        self::assertEquals($expectedData['DE']['bban_structure'], $registry->getBbanStructure('DE'));
        self::assertEquals($expectedData['DE']['iban_regex'], $registry->getIbanRegex('DE'));
        self::assertEquals($expectedData['DE']['bban_regex'], $registry->getBbanRegex('DE'));
        self::assertEquals($expectedData['DE']['iban_length'], $registry->getIbanLength('DE'));
        self::assertEquals($expectedData['DE']['bban_length'], $registry->getBbanLength('DE'));
        self::assertEquals($expectedData['DE']['iban_electronic_format_example'], $registry->getIbanElectronicFormatExample('DE'));
        self::assertEquals($expectedData['DE']['iban_print_format_example'], $registry->getIbanPrintFormatExample('DE'));
        self::assertEquals(0, $registry->getBbanBankIdentifierStartPos('DE'));
        self::assertEquals(8, $registry->getBbanBankIdentifierEndPos('DE'));
    }
}
