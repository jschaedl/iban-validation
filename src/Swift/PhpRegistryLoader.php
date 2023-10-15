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
 * Loads the iban_registry php file.
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
final class PhpRegistryLoader implements RegistryLoaderInterface
{
    public function load(): array
    {
        return require dirname(__DIR__, 2).'/Resource/iban_registry.php';
    }
}
