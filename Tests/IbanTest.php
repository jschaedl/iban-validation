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
use PHPUnit\Framework\TestCase;

final class IbanTest extends TestCase
{
    /**
     * @dataProvider ibanProvider
     */
    public function testIbanCreation(
        string $iban,
        string $expectedCountryCode,
        string $expectedChecksum,
        string $expectedBban,
        string $expectedBbanBankIdentifier,
        string $expectedFormatElectronic,
        string $expectedFormatPrint,
        string $expectedFormatAnonymized
    ) {
        $iban = new Iban($iban);

        $this->assertEquals($expectedCountryCode, $iban->countryCode());
        $this->assertEquals($expectedChecksum, $iban->checksum());
        $this->assertEquals($expectedBban, $iban->bban());
        $this->assertEquals($expectedBbanBankIdentifier, $iban->bbanBankIdentifier());

        $this->assertEquals($expectedFormatElectronic, $iban->format(Iban::FORMAT_ELECTRONIC));
        $this->assertEquals($expectedFormatPrint, $iban->format(Iban::FORMAT_PRINT));
        $this->assertEquals($expectedFormatAnonymized, $iban->format(Iban::FORMAT_ANONYMIZED));
    }

    public function ibanProvider()
    {
        yield [
            'IBAN DE45 5005 0201 1241 5398 70',
            'DE',
            '45',
            '500502011241539870',
            '50050201',
            'DE45500502011241539870',
            'DE45 5005 0201 1241 5398 70',
            'XXXXXXXXXXXXXXXXXX9870'
        ];

        yield [
            'IBAN CH45 5005 0201 1241 5398 7',
            'CH',
            '45',
            '50050201124153987',
            '50050',
            'CH4550050201124153987',
            'CH45 5005 0201 1241 5398 7',
            'XXXXXXXXXXXXXXXXX3987'
        ];

        yield [
            'ST68000200010192194210112',
            'ST',
            '68',
            '000200010192194210112',
            '0002',
            'ST68000200010192194210112',
            'ST68 0002 0001 0192 1942 1011 2',
            'XXXXXXXXXXXXXXXXXXXXX0112'
        ];

        yield [
            'PL61109010140000071219812874',
            'PL',
            '61',
            '109010140000071219812874',
            '109010140000071219812874',
            'PL61109010140000071219812874',
            'PL61 1090 1014 0000 0712 1981 2874',
            'XXXXXXXXXXXXXXXXXXXXXXXX2874'
        ];
    }
}
