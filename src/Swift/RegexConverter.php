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

/**
 * Converts iban and bban structure notation used in iban_registry text file provided by SWIFT to common regex.
 *
 * Example: 'DE2!n8!n16!c' => 'DE\d{2}\d{8}[A-Z0-9]{16}'
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
class RegexConverter
{
    /**
     * @param string $input
     * @return string
     */
    public function convert($input)
    {
        $input = preg_replace('/(^[A-Z]{2})/', '${1}', $input);
        $input = preg_replace('/(\d+)\!(n)/', '\d{${1}}', $input);
        $input = preg_replace('/(\d+)\!(c)/', '[A-Z0-9]{${1}}', $input);
        $input = preg_replace('/(\d+)\!(a)/', '[A-Z]{${1}}', $input);

        return $input;
    }
}
