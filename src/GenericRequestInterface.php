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

namespace UaRequest;

interface GenericRequestInterface
{
    /**
     * @return array<string, string>
     *
     * @throws void
     */
    public function getHeaders(): array;

    /**
     * @return array<string>
     *
     * @throws void
     */
    public function getFilteredHeaders(): array;

    /** @throws void */
    public function getBrowserUserAgent(): string;

    /** @throws void */
    public function getDeviceUserAgent(): string;

    /** @throws void */
    public function getPlatformUserAgent(): string;

    /** @throws void */
    public function getEngineUserAgent(): string;
}
