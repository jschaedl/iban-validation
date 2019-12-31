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
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator([
            'violation.unsupported_country' => 'unsupported_country',
            'violation.invalid_length' => 'invalid_length',
            'violation.invalid_format' => 'invalid_format',
            'violation.invalid_checksum' => 'invalid_checksum',
        ]);
    }

    public function validIbanDataProvider()
    {
        yield ['AD1200012030200359100100'];
        yield ['AE070331234567890123456'];
        yield ['AL47212110090000000235698741'];
        yield ['AT611904300234573201'];
        yield ['AZ21NABZ00000000137010001944'];
        yield ['BA391290079401028494'];
        yield ['BE68539007547034'];
        yield ['BG80BNBG96611020345678'];
        yield ['BH67BMAG00001299123456'];
        yield ['BR1800360305000010009795493C1'];
        yield ['BY13NBRB3600900000002Z00AB00'];
        yield ['CH9300762011623852957'];
        yield ['CR05015202001026284066'];
        yield ['CY17002001280000001200527600'];
        yield ['CZ6508000000192000145399'];
        yield ['DE89370400440532013000'];
        yield ['DK5000400440116243'];
        yield ['DO28BAGR00000001212453611324'];
        yield ['EE382200221020145685'];
        yield ['ES9121000418450200051332'];
        yield ['FI2112345600000785'];
        yield ['FO6264600001631634'];
        yield ['FR1420041010050500013M02606'];
        yield ['GB29NWBK60161331926819'];
        yield ['GE29NB0000000101904917'];
        yield ['GI75NWBK000000007099453'];
        yield ['GL8964710001000206'];
        yield ['GR1601101250000000012300695'];
        yield ['GT82TRAJ01020000001210029690'];
        yield ['HR1210010051863000160'];
        yield ['HU42117730161111101800000000'];
        yield ['IE29AIBK93115212345678'];
        yield ['IL620108000000099999999'];
        yield ['IQ98NBIQ850123456789012'];
        yield ['IS140159260076545510730339'];
        yield ['IT60X0542811101000000123456'];
        yield ['JO94CBJO0010000000000131000302'];
        yield ['KW81CBKU0000000000001234560101'];
        yield ['KZ86125KZT5004100100'];
        yield ['LB62099900000001001901229114'];
        yield ['LC55HEMM000100010012001200023015'];
        yield ['LI21088100002324013AA'];
        yield ['LT121000011101001000'];
        yield ['LU280019400644750000'];
        yield ['LV80BANK0000435195001'];
        yield ['MC5811222000010123456789030'];
        yield ['MD24AG000225100013104168'];
        yield ['ME25505000012345678951'];
        yield ['MK07250120000058984'];
        yield ['MR1300020001010000123456753'];
        yield ['MT84MALT011000012345MTLCAST001S'];
        yield ['MU17BOMM0101101030300200000MUR'];
        yield ['NL91ABNA0417164300'];
        yield ['NO9386011117947'];
        yield ['PK36SCBL0000001123456702'];
        yield ['PL61109010140000071219812874'];
        yield ['PS92PALS000000000400123456702'];
        yield ['PT50000201231234567890154'];
        yield ['QA58DOHB00001234567890ABCDEFG'];
        yield ['RO49AAAA1B31007593840000'];
        yield ['RS35260005601001611379'];
        yield ['SA0380000000608010167519'];
        yield ['SC18SSCB11010000000000001497USD'];
        yield ['SE4550000000058398257466'];
        yield ['SI56263300012039086'];
        yield ['SK3112000000198742637541'];
        yield ['SM86U0322509800000000270100'];
        //yield ['ST68000200010192194210112']; // given with invalid checksum by swift
        yield ['SV62CENR00000000000000700025'];
        yield ['TL380080012345678910157'];
        yield ['TN5910006035183598478831'];
        yield ['TR330006100519786457841326'];
        yield ['UA213223130000026007233566001'];
        yield ['VG96VPVG0000012345678901'];
        yield ['XK051212012345678906'];
    }

    /**
     * @dataProvider validIbanDataProvider
     * @param $iban
     */
    public function testValidIbans($iban)
    {
        $this->assertTrue(
            $this->validator->validate(new Iban($iban)),
            sprintf('Iban shoud be valid, violations: %s', implode(' ', $this->validator->getViolations()))
        );
    }

    public function invalidIbanDataProvider()
    {
        yield ['AD1200012030200359100120'];
        yield ['AT611904300234573221'];
        yield ['BA39129007940028494'];
        yield ['BE685390047034'];
        yield ['VG611904300234573201'];
        yield ['ST68000200010192194210112'];
        yield ['MCBKCWCU25727002'];
    }

    public function testItShouldCreateIbanWithIbanAsObject()
    {
        $iban = 'DE89370400440532013000';
        $this->assertTrue($this->validator->validate(new Iban($iban)));
    }

    public function testItShouldCreateIbanWithIbanAsString()
    {
        $iban = 'DE89370400440532013000';
        $this->assertTrue($this->validator->validate($iban));
    }

    /**
     * @dataProvider invalidIbanDataProvider
     */
    public function testInvalidIbans($iban)
    {
        $this->assertFalse($this->validator->validate(new Iban($iban)));
    }

    public function testIbanCountryCodeValidation()
    {
        $isValid = $this->validator->validate(new Iban('ZZ89 3704 0044 0532 0130 00'));
        $violations = $this->validator->getViolations();

        $this->assertFalse($isValid);
        $this->assertCount(1, $violations);
        $this->assertContains('unsupported_country', $violations);
    }

    public function testIbanLengthValidation()
    {
        $isValid = $this->validator->validate(new Iban('DE89 3704 0044 0530 7877 089'));
        $violations = $this->validator->getViolations();

        $this->assertFalse($isValid);
        $this->assertCount(3, $violations);
        $this->assertContains('invalid_length', $violations);
        $this->assertContains('invalid_format', $violations);
        $this->assertContains('invalid_checksum', $violations);
    }

    public function testIbanFormatValidation()
    {
        $isValid = $this->validator->validate(new Iban('DE89 3704 0044 053A 013B 00'));
        $violations = $this->validator->getViolations();

        $this->assertFalse($isValid);
        $this->assertCount(2, $violations);
        $this->assertContains('invalid_format', $violations);
        $this->assertContains('invalid_checksum', $violations);
    }

    public function testIbanChecksumValidation()
    {
        $isValid = $this->validator->validate(new Iban('DE90 3704 0044 0532 0130 00'));
        $violations = $this->validator->getViolations();

        $this->assertFalse($isValid);
        $this->assertCount(1, $violations);
        $this->assertContains('invalid_checksum', $violations);
    }
}
