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
use UaParser\ClientCodeInterface;
use UaParser\ClientVersionInterface;
use UaParser\PlatformCodeInterface;
use UaParser\PlatformVersionInterface;

final class XRequestedWith implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    public function __construct(
        string $value,
        private readonly ClientCodeInterface $clientCode,
        private readonly ClientVersionInterface $clientVersion,
        private readonly PlatformCodeInterface $platformCode,
    ) {
        $this->value = $value;
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

    /** @throws void */
    #[Override]
    public function getPlatformCode(string | null $derivate = null): string | null
    {
        return $this->platformCode->getPlatformCode($this->value, $derivate);
    }
}
