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

class IbanTest extends \PHPUnit_Framework_TestCase
{
    public function testIbanCreation()
    {
        $iban = new IBAN('DE45500502011241539870');

        $this->assertEquals('DE', $iban->getLocaleCode());
        $this->assertEquals('45', $iban->getChecksum());
        $this->assertEquals('50050201', $iban->getInstituteIdentification());
        $this->assertEquals('1241539870', $iban->getBankAccountNumber());
        $this->assertEquals('500502011241539870', $iban->getAccountIdentification());

        $this->assertEquals('DE45500502011241539870', (string) $iban);
        $this->assertEquals('DE45 5005 0201 1241 5398 70', $iban->format());
    }
}
