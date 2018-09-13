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

class IbanTest extends TestCase
{
    /**
     * @dataProvider ibanProvider
     */
    public function testIbanCreation(
        string $iban,
        string $expectedCountryCode,
        string $expectedChecksum,
        string $expectedBban,
        string $expectedFormatElectronic,
        string $expectedFormatPrint,
        string $expectedFormatAnonymized
    ) {
        $iban = new Iban($iban);

        $this->assertEquals($expectedCountryCode, $iban->getCountryCode());
        $this->assertEquals($expectedChecksum, $iban->getChecksum());
        $this->assertEquals($expectedBban, $iban->getBban());

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
            'DE45500502011241539870',
            'DE45 5005 0201 1241 5398 70',
            'XXXXXXXXXXXXXXXXXX9870'
        ];

        yield [
            'IBAN CH45 5005 0201 1241 5398 7',
            'CH',
            '45',
            '50050201124153987',
            'CH4550050201124153987',
            'CH45 5005 0201 1241 5398 7',
            'XXXXXXXXXXXXXXXXX3987'
        ];

        yield ['ST68000200010192194210112',
            'ST',
            '68',
            '000200010192194210112',
            'ST68000200010192194210112',
            'ST68 0002 0001 0192 1942 1011 2',
            'XXXXXXXXXXXXXXXXXXXXX0112'
        ];
    }
}
