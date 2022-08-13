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

final class ValidatorTest extends TestCase
{
    use ValidatorDataProviderTrait;

    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp(): void
    {
        $this->validator = new Validator([
            'violation.unsupported_country' => 'unsupported_country',
            'violation.invalid_length' => 'invalid_length',
            'violation.invalid_format' => 'invalid_format',
            'violation.invalid_checksum' => 'invalid_checksum',
        ]);
    }

    /**
     * @dataProvider validIbanDataProvider
     *
     * @param $iban
     */
    public function testValidIbans($iban)
    {
        $this->assertTrue(
            $this->validator->validate(new Iban($iban)),
            sprintf('Iban shoud be valid, violations: %s', implode(' ', $this->validator->getViolations()))
        );
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
