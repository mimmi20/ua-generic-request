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

final class UaOs implements HeaderInterface
{
    /** @throws void */
    public function __construct(private readonly string $value)
    {
        // nothing to do
    }

    /**
     * Retrieve header value
     *
     * @throws void
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /** @throws void */
    public function hasDeviceInfo(): bool
    {
        return false;
    }

    /** @throws void */
    public function hasBrowserInfo(): bool
    {
        return false;
    }

    /** @throws void */
    public function hasPlatformInfo(): bool
    {
        return true;
    }

    /** @throws void */
    public function hasEngineInfo(): bool
    {
        return false;
    }
}
