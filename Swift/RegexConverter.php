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

class RegexConverter
{
    public function convert($input)
    {
        $input = preg_replace('/(^[A-Z]{2})/', '${1}', $input);
        $input = preg_replace('/(\d+)\!(n)/', '\d{${1}}', $input);
        $input = preg_replace('/(\d+)\!(c)/', '[A-Z0-9]{${1}}', $input);
        $input = preg_replace('/(\d+)\!(a)/', '[A-Z]{${1}}', $input);

        return $input;
    }
}
