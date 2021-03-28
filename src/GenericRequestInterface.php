<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2021, Thomas Mueller <mimmi20@live.de>
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
     */
    public function getHeaders(): array;

    /**
     * @return array<string>
     */
    public function getFilteredHeaders(): array;

    public function getBrowserUserAgent(): string;

    public function getDeviceUserAgent(): string;

    public function getPlatformUserAgent(): string;

    public function getEngineUserAgent(): string;
}
