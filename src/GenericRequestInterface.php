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

namespace UaRequest;

use UaRequest\Header\HeaderInterface;

interface GenericRequestInterface
{
    /**
     * @return array<non-empty-string, non-empty-string>
     *
     * @throws void
     */
    public function getHeaders(): array;

    /**
     * @return array<non-empty-string, HeaderInterface>
     *
     * @throws void
     */
    public function getFilteredHeaders(): array;

    /** @throws void */
    public function getHash(): string;
}
