<?php

/**
 * This file is part of the mimmi20/ua-generic-request package.
 *
 * Copyright (c) 2015-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequest\Header;

use UaRequest\Constants;
use UaRequest\NotFoundException;

interface HeaderLoaderInterface
{
    /**
     * @param Constants::HEADER_* $key
     *
     * @throws void
     */
    public function has(string $key): bool;

    /**
     * @param Constants::HEADER_* $key
     *
     * @throws NotFoundException
     */
    public function load(string $key, string $value): HeaderInterface;
}
