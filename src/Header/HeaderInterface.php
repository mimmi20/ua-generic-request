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

namespace UaRequest\Header;

interface HeaderInterface
{
    /**
     * Retrieve header value
     */
    public function getValue(): string;

    public function hasDeviceInfo(): bool;

    public function hasBrowserInfo(): bool;

    public function hasPlatformInfo(): bool;

    public function hasEngineInfo(): bool;
}
