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
use UaNormalizer\Normalizer\Exception\Exception;
use UaNormalizer\NormalizerFactory;
use UaParser\ClientCodeInterface;
use UaParser\ClientVersionInterface;
use UaParser\DeviceCodeInterface;
use UaParser\DeviceParserInterface;
use UaParser\EngineCodeInterface;
use UaParser\EngineParserInterface;

use UaParser\PlatformCodeInterface;
use function mb_strtolower;
use function preg_match;

final class XOperaminiPhoneUa implements HeaderInterface
{
    use HeaderTrait;

    /** @throws Exception */
    public function __construct(
        string $value,
        private readonly DeviceCodeInterface $deviceCode,
        private readonly ClientCodeInterface $clientCode,
        private readonly ClientVersionInterface $clientVersion,
        private readonly PlatformCodeInterface $platformCode,
        private readonly EngineCodeInterface $engineCode,
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
    public function getClientCode(): string
    {
        return $this->clientCode->getClientCode($this->value);
    }

    /** @throws void */
    #[Override]
    public function hasClientVersion(): bool
    {
        return $this->clientVersion->hasClientVersion($this->value);
    }

    /**
     * @throws void
     */
    #[Override]
    public function getClientVersion(string | null $code = null): string | null
    {
        return $this->clientVersion->getClientVersion($this->value, $code);
    }

    /** @throws void */
    #[Override]
    public function hasPlatformCode(): bool
    {
        return $this->platformCode->hasPlatformCode($this->value);
    }

    /**
     * @throws void
     */
    #[Override]
    public function getPlatformCode(string | null $derivate = null): string | null
    {
        return $this->platformCode->getPlatformCode($this->value, $derivate);
    }

    /** @throws void */
    #[Override]
    public function hasEngineCode(): bool
    {
        return $this->engineCode->hasEngineCode($this->value);
    }

    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getEngineCode(string | null $code = null): string | null
    {
        return $this->engineCode->getEngineCode($this->value);
    }
}
