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
use Iban\Validation\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;
    protected $ibans;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function validIbanDataProvider()
    {
        yield ['AT611904300234573201'];
        yield ['BA391290079401028494'];
        yield ['BE68539007547034'];
        yield ['BE43068999999501'];
        yield ['BG80BNBG96611020345678'];
        yield ['CH9300762011623852957'];
        yield ['CY17002001280000001200527600'];
        yield ['CZ6508000000192000145399'];
        yield ['DE89 3704 0044 0532 0130 00'];
        yield ['DK5000400440116243'];
        yield ['EE382200221020145685'];
        yield ['ES9121000418450200051332'];
        yield ['FR1420041010050500013M02606'];
        yield ['FI2112345600000785'];
        yield ['GB29NWBK60161331926819'];
        yield ['GI75NWBK000000007099453'];
        yield ['GR1601101250000000012300695'];
        yield ['HR1210010051863000160'];
        yield ['HU42117730161111101800000000'];
        yield ['IE29AIBK93115212345678'];
        yield ['IL620108000000099999999'];
        yield ['IS140159260076545510730339'];
        yield ['IT60X0542811101000000123456'];
        yield ['LI21088100002324013AA'];
        yield ['LT121000011101001000'];
        yield ['LU280019400644750000'];
        yield ['LV80BANK0000435195001'];
        yield ['MC1112739000700011111000H79'];
        yield ['ME25505000012345678951'];
        yield ['MK07250120000058984'];
        yield ['MT84MALT011000012345MTLCAST001S'];
        yield ['MU17BOMM0101101030300200000MUR'];
        yield ['NL91ABNA0417164300'];
        yield ['NO9386011117947'];
        yield ['PL61109010140000071219812874'];
        yield ['PT50000201231234567890154'];
        yield ['RO49AAAA1B31007593840000'];
        yield ['RS35260005601001611379'];
        yield ['SE9412312345678901234561'];
        yield ['SI56191000000123438'];
        yield ['SK3112000000198742637541'];
        yield ['SM86U0322509800000000270100'];
        yield ['TN5914207207100707129648'];
        yield ['TR330006100519786457841326'];
    }

    /**
     * @dataProvider validIbanDataProvider
     */
    public function testValidIbans($iban)
    {
        $this->assertTrue($this->validator->validate(new Iban($iban)));
    }

    public function invalidIbanDataProvider()
    {
        yield ['AD1200012030200359100120'];
        yield ['AT611904300234573221'];
        yield ['BA39129007940028494'];
        yield ['BE685390047034'];
        yield ['AA611904300234573201'];
    }

    /**
     * @dataProvider invalidIbanDataProvider
     */
    public function testInvalidIbans($iban)
    {
        $this->assertFalse($this->validator->validate(new Iban($iban)));
    }

    /**
     * @expectedException Iban\Validation\Exception\UnexpectedTypeException
     */
    public function testItShouldThrowExceptionForNonIban()
    {
        $this->assertFalse($this->validator->validate(new \stdClass()));
    }
}
