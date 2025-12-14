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

use BrowserDetector\Version\VersionInterface;
use Override;
use UaParser\PlatformCodeInterface;
use UaParser\PlatformVersionInterface;

final class PlatformHeader implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    public function __construct(
        string $value,
        private readonly PlatformCodeInterface $platformCode,
        private readonly PlatformVersionInterface $platformVersion,
    ) {
        $this->value = $value;
    }

    /** @throws void */
    #[Override]
    public function hasPlatformCode(): bool
    {
        return $this->platformCode->hasPlatformCode($this->value);
    }

    /** @throws void */
    #[Override]
    public function getPlatformCode(string | null $derivate = null): string | null
    {
        return $this->platformCode->getPlatformCode($this->value, $derivate);
    }

    /** @throws void */
    #[Override]
    public function hasPlatformVersion(): bool
    {
        return $this->platformCode->hasPlatformCode($this->value)
            && $this->platformVersion->hasPlatformVersion($this->value);
    }

    /** @throws void */
    #[Override]
    public function getPlatformVersion(string | null $code = null): VersionInterface
    {
        return $this->platformVersion->getPlatformVersion($this->value, $code);
    }
}
