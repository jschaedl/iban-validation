<?php

/*
 * This file is part of the iban-validation library.
 *
 * (c) Jan Schädlich <mail@janschaedlich.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Iban\Validation\Swift\Exception;

/**
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
class RegexConversionException extends \RuntimeException
{
    public function __construct(string $input)
    {
        parent::__construct(sprintf('Can not convert given input "%s".', $input));
    }
}
