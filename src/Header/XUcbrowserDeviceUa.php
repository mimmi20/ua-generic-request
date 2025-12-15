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

use Override;
use UaData\OsInterface;
use UaParser\DeviceCodeInterface;
use UaParser\PlatformCodeInterface;
use UaRequest\Exception\NotFoundException;

final class XUcbrowserDeviceUa implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    public function __construct(
        string $value,
        private readonly DeviceCodeInterface $deviceCode,
        private readonly PlatformCodeInterface $platformCode,
    ) {
        $this->value = $value;
    }

    /** @throws void */
    #[Override]
    public function hasDeviceCode(): bool
    {
        return $this->deviceCode->hasDeviceCode($this->value);
    }

    /** @throws void */
    #[Override]
    public function getDeviceCode(): string | null
    {
        return $this->deviceCode->getDeviceCode($this->value);
    }

    /** @throws void */
    #[Override]
    public function hasPlatformCode(): bool
    {
        return $this->platformCode->hasPlatformCode($this->value);
    }

    /** @throws NotFoundException */
    #[Override]
    public function getPlatformCode(string | null $derivate = null): OsInterface
    {
        return $this->platformCode->getPlatformCode($this->value, $derivate);
    }
}
