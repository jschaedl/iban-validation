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
class RegistryLoaderException extends \RuntimeException
{
    public function __construct(string $filename)
    {
        parent::__construct(sprintf('Can not load contents of file "%s".', $filename));
    }
}
