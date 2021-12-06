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
 * Loads the iban_registry data.
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
interface RegistryLoaderInterface
{
    public function load(): array;
}
