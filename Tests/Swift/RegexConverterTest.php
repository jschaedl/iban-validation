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

use Iban\Validation\Swift\RegexConverter;
use PHPUnit\Framework\TestCase;

class RegexConverterTest extends TestCase
{
    public function testConvertToRegex()
    {
        $converter = new RegexConverter();

        $this->assertEquals('\d{2}', $converter->convert('2!n'));
        $this->assertEquals('\d{2}\d{2}', $converter->convert('2!n2!n'));
        $this->assertEquals('\d{8}', $converter->convert('8!n'));
        $this->assertEquals('\d{16}', $converter->convert('16!n'));
        $this->assertEquals('[A-Z0-9]{2}', $converter->convert('2!c'));
        $this->assertEquals('[A-Z0-9]{8}', $converter->convert('8!c'));
        $this->assertEquals('[A-Z0-9]{16}', $converter->convert('16!c'));
        $this->assertEquals('AL', $converter->convert('AL'));

        $this->assertEquals('AL\d{2}\d{8}[A-Z0-9]{16}', $converter->convert('AL2!n8!n16!c'));
        $this->assertEquals('DE\d{2}\d{8}[A-Z0-9]{16}', $converter->convert('DE2!n8!n16!c'));
        $this->assertEquals('IE\d{2}[A-Z]{4}\d{6}\d{8}', $converter->convert('IE2!n4!a6!n8!n'));
    }
}
