<?php

namespace Iban\Validation\Tests;

use Iban\Validation\BareValidator;
use Iban\Validation\Iban;
use PHPUnit\Framework\TestCase;

class BareValidatorTest extends TestCase
{
    use ValidatorDataProviderTrait;

    /**
     * @var BareValidator
     */
    protected $validator;

    protected function setUp(): void
    {
        $this->validator = new BareValidator();
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
            'Iban shoud be valid'
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
    public function testInvalidIbans($iban, $exceptionClass)
    {
        $this->expectException($exceptionClass);
        $this->validator->validate(new Iban($iban));
    }
}
