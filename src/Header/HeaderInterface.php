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

interface HeaderInterface
{
    /**
     * Retrieve header value
     *
     * @throws void
     */
    public function getValue(): string;

    /** @throws void */
    public function hasDeviceInfo(): bool;

    /** @throws void */
    public function hasBrowserInfo(): bool;

    /** @throws void */
    public function hasPlatformInfo(): bool;

    /** @throws void */
    public function hasEngineInfo(): bool;
}
