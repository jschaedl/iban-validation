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

class UnsupportedCountryCodeException extends \RuntimeException
{
    /**
     * @param string $countryCode
     */
    public function __construct($countryCode)
    {
        parent::__construct(sprintf('Country with countryCode "%s" is not supported', $countryCode));
    }
}
