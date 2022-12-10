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

final class RegexConverterTest extends TestCase
{
    public function test_convert_to_regex(): void
    {
        $converter = new RegexConverter();

        self::assertEquals('\d{2}', $converter->convert('2!n'));
        self::assertEquals('\d{2}\d{2}', $converter->convert('2!n2!n'));
        self::assertEquals('\d{8}', $converter->convert('8!n'));
        self::assertEquals('\d{16}', $converter->convert('16!n'));
        self::assertEquals('[A-Z0-9]{2}', $converter->convert('2!c'));
        self::assertEquals('[A-Z0-9]{8}', $converter->convert('8!c'));
        self::assertEquals('[A-Z0-9]{16}', $converter->convert('16!c'));
        self::assertEquals('AL', $converter->convert('AL'));

        self::assertEquals('AL\d{2}\d{8}[A-Z0-9]{16}', $converter->convert('AL2!n8!n16!c'));
        self::assertEquals('DE\d{2}\d{8}[A-Z0-9]{16}', $converter->convert('DE2!n8!n16!c'));
        self::assertEquals('IE\d{2}[A-Z]{4}\d{6}\d{8}', $converter->convert('IE2!n4!a6!n8!n'));
    }
}
