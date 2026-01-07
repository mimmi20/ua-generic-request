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
use Override;
use UaData\EngineInterface;
use UaData\OsInterface;
use UaParser\ClientCodeInterface;
use UaParser\ClientVersionInterface;
use UaParser\DeviceCodeInterface;
use UaParser\EngineCodeInterface;
use UaParser\EngineVersionInterface;
use UaParser\PlatformCodeInterface;
use UaParser\PlatformVersionInterface;
use UaRequest\Exception\NotFoundException;

final class FullHeader implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    public function __construct(
        string $value,
        private readonly DeviceCodeInterface $deviceCode,
        private readonly ClientCodeInterface $clientCode,
        private readonly ClientVersionInterface $clientVersion,
        private readonly PlatformCodeInterface $platformCode,
        private readonly PlatformVersionInterface $platformVersion,
        private readonly EngineCodeInterface $engineCode,
        private readonly EngineVersionInterface $engineVersion,
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
    public function hasClientCode(): bool
    {
        return $this->clientCode->hasClientCode($this->value);
    }

    /** @throws void */
    #[Override]
    public function getClientCode(): string | null
    {
        return $this->clientCode->getClientCode($this->value);
    }

    /** @throws void */
    #[Override]
    public function hasClientVersion(): bool
    {
        return $this->clientVersion->hasClientVersion($this->value);
    }

    /** @throws void */
    #[Override]
    public function getClientVersion(string | null $code = null): VersionInterface
    {
        return $this->clientVersion->getClientVersion($this->value, $code);
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

    /** @throws void */
    #[Override]
    public function hasPlatformVersion(): bool
    {
        return $this->platformVersion->hasPlatformVersion($this->value);
    }

    /** @throws void */
    #[Override]
    public function getPlatformVersion(string | null $code = null): VersionInterface
    {
        return $this->platformVersion->getPlatformVersion($this->value, $code);
    }

    /** @throws void */
    #[Override]
    public function hasEngineCode(): bool
    {
        return $this->engineCode->hasEngineCode($this->value);
    }

    /** @throws NotFoundException */
    #[Override]
    public function getEngineCode(): EngineInterface
    {
        return $this->engineCode->getEngineCode($this->value);
    }

    /** @throws void */
    #[Override]
    public function hasEngineVersion(): bool
    {
        return $this->engineVersion->hasEngineVersion($this->value);
    }

    /** @throws void */
    #[Override]
    public function getEngineVersion(string | null $code = null): VersionInterface
    {
        return $this->engineVersion->getEngineVersion($this->value, $code);
    }
}
