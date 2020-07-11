<?php

/*
 * This file is part of the iban-validation library.
 *
 * (c) Jan Schädlich <mail@janschaedlich.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Iban\Validation\Swift;

use Iban\Validation\Swift\Exception\RegexConversionException;

/**
 * Converts iban and bban structure notation used in iban_registry text file provided by SWIFT to common regex.
 *
 * Example: 'DE2!n8!n16!c' => 'DE\d{2}\d{8}[A-Z0-9]{16}'
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 *
 * @internal the RegexConverter is an internal helper class, you should not use it directly
 */
final class RegexConverter
{
    public function convert(string $input): string
    {
        $searchPatterns = [
            '/(^[A-Z]{2})/',
            '/(\d+)\!(n)/',
            '/(\d+)\!(c)/',
            '/(\d+)\!(a)/',
        ];

        $replacements = [
            '${1}',
            '\d{${1}}',
            '[A-Z0-9]{${1}}',
            '[A-Z]{${1}}',
        ];

        if (null === $convertedInput = preg_replace($searchPatterns, $replacements, $input)) {
            throw new RegexConversionException($input);
        }

        return $convertedInput;
    }
}
