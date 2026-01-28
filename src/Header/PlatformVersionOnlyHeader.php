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

use BrowserDetector\Version\VersionInterface;
use Deprecated;
use Override;
use UaData\OsInterface;
use UaParser\PlatformVersionInterface;

final class PlatformVersionOnlyHeader implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    public function __construct(string $value, private readonly PlatformVersionInterface $platformVersion)
    {
        $this->value = $value;
    }

    /** @throws void */
    #[Override]
    public function hasPlatformVersion(): bool
    {
        return $this->platformVersion->hasPlatformVersion($this->value);
    }

    /** @throws void */
    #[Override]
    #[Deprecated(message: 'use getPlatformVersionWithOs() instead', since: '15.0.6')]
    public function getPlatformVersion(string | null $code = null): VersionInterface
    {
        return $this->platformVersion->getPlatformVersion($this->value, $code);
    }

    /** @throws void */
    #[Override]
    public function getPlatformVersionWithOs(OsInterface $os): VersionInterface
    {
        return $this->platformVersion->getPlatformVersionWithOs($this->value, $os);
    }
}
