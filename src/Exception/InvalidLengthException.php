<?php

/*
 * This file is part of the iban-validation library.
 *
 * (c) Jan Schädlich <mail@janschaedlich.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Iban\Validation\Exception;

/**
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
class InvalidLengthException extends \RuntimeException
{
    public function __construct(string $iban)
    {
        parent::__construct(sprintf('Length of given IBAN "%s" is not valid.', $iban));
    }
}
