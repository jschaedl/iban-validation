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

class InvalidChecksumException extends \RuntimeException
{
    protected $validChecksum;

    public function __construct($iban, $validChecksum)
    {
        $this->validChecksum = $validChecksum;

        parent::__construct(sprintf('Checksum of given IBAN "%s" is not valid! Valid checksum is %s', $iban, $validChecksum));
    }

    public function getValidChecksum()
    {
        return $this->validChecksum;
    }
}
