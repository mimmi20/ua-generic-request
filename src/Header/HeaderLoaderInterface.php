<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2023, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequest\Header;

use UaRequest\NotFoundException;

interface HeaderLoaderInterface
{
    /** @throws void */
    public function has(string $key): bool;

    /** @throws NotFoundException */
    public function load(string $key, string $value): HeaderInterface;
}
