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

use function mb_strtolower;
use function preg_match;

final class BaiduFlyflow implements HeaderInterface
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Retrieve header value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function hasDeviceInfo(): bool
    {
        $hasMatch = preg_match('/;htc;htc;/i', mb_strtolower($this->value));

        return false === $hasMatch || 0 === $hasMatch;
    }

    public function hasBrowserInfo(): bool
    {
        return false;
    }

    public function hasPlatformInfo(): bool
    {
        return false;
    }

    public function hasEngineInfo(): bool
    {
        return false;
    }
}
