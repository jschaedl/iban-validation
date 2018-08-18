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

use Iban\Validation\Swift\Exception\UnsupportedCountryCodeException;

class Registry
{
    protected $registry;

    public function __construct()
    {
        $swiftRegistryLoader = new RegistryLoader();
        $this->registry = $swiftRegistryLoader->load(__DIR__ . '/iban_registry.yaml');
    }

    public function isCountryAvailable($countryCode)
    {
        return array_key_exists($countryCode, $this->registry);
    }

    public function getIbanRegex($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return $this->registry[$countryCode]['iban_regex'];
    }

    public function getBbanRegex($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return $this->registry[$countryCode]['bban_regex'];
    }

    public function getBbanLength($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return $this->registry[$countryCode]['bban_length'];
    }

    public function getIbanLength($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return $this->registry[$countryCode]['iban_length'];
    }

    private function guardAgainstUnsupportedCountryCode($countryCode)
    {
        if (!$this->isCountryAvailable($countryCode)) {
            throw new UnsupportedCountryCodeException($countryCode);
        }
    }
}
