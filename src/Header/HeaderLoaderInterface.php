<?php

/**
 * This file is part of the mimmi20/ua-generic-request package.
 *
 * Copyright (c) 2015-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequest\Header;

use UaRequest\Exception\NotFoundException;

interface HeaderLoaderInterface
{
    /**
     * @param non-empty-string $key
     *
     * @throws void
     */
    public function has(string $key): bool;

    /**
     * @param non-empty-string $key
     *
     * @throws NotFoundException
     */
    public function load(string $key, string $value): HeaderInterface;
}
