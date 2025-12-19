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

use BrowserDetector\Version\NullVersion;
use BrowserDetector\Version\VersionInterface;
use Override;
use UaData\EngineInterface;
use UaData\OsInterface;
use UaRequest\Exception\NotFoundException;
use UaResult\Bits\Bits;
use UaResult\Device\Architecture;
use UaResult\Device\FormFactor;

// @phpcs:disable SlevomatCodingStandard.Classes.RequireConstructorPropertyPromotion.RequiredConstructorPropertyPromotion
trait HeaderTrait
{
    private string $value;

    /** @throws void */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Retrieve header value
     *
     * @throws void
     */
    #[Override]
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Retrieve normalized header value
     *
     * @throws void
     */
    #[Override]
    public function getNormalizedValue(): string
    {
        return $this->value;
    }

    /** @throws void */
    #[Override]
    public function hasDeviceArchitecture(): bool
    {
        return false;
    }

    /** @throws void */
    #[Override]
    public function getDeviceArchitecture(): Architecture
    {
        return Architecture::unknown;
    }

    /** @throws void */
    #[Override]
    public function hasDeviceFormFactor(): bool
    {
        return false;
    }

    /**
     * @return list<FormFactor>
     *
     * @throws void
     */
    #[Override]
    public function getDeviceFormFactor(): array
    {
        return [FormFactor::unknown];
    }

    /** @throws void */
    #[Override]
    public function hasDeviceBitness(): bool
    {
        return false;
    }

    /** @throws void */
    #[Override]
    public function getDeviceBitness(): Bits
    {
        return Bits::unknown;
    }

    /** @throws void */
    #[Override]
    public function hasDeviceIsMobile(): bool
    {
        return false;
    }

    /** @throws void */
    #[Override]
    public function getDeviceIsMobile(): bool | null
    {
        return null;
    }

    /** @throws void */
    #[Override]
    public function hasDeviceCode(): bool
    {
        return false;
    }

    /** @throws void */
    #[Override]
    public function getDeviceCode(): string | null
    {
        return null;
    }

    /** @throws void */
    #[Override]
    public function hasDeviceIsWow64(): bool
    {
        return false;
    }

    /** @throws void */
    #[Override]
    public function getDeviceIsWow64(): bool | null
    {
        return null;
    }

    /** @throws void */
    #[Override]
    public function hasClientCode(): bool
    {
        return false;
    }

    /** @throws void */
    #[Override]
    public function getClientCode(): string | null
    {
        return null;
    }

    /** @throws void */
    #[Override]
    public function hasClientVersion(): bool
    {
        return false;
    }

    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getClientVersion(string | null $code = null): VersionInterface
    {
        return new NullVersion();
    }

    /** @throws void */
    #[Override]
    public function hasPlatformCode(): bool
    {
        return false;
    }

    /**
     * @throws NotFoundException
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getPlatformCode(string | null $derivate = null): OsInterface
    {
        throw new NotFoundException();
    }

    /** @throws void */
    #[Override]
    public function hasPlatformVersion(): bool
    {
        return false;
    }

    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getPlatformVersion(string | null $code = null): VersionInterface
    {
        return new NullVersion();
    }

    /** @throws void */
    #[Override]
    public function hasEngineCode(): bool
    {
        return false;
    }

    /** @throws NotFoundException */
    #[Override]
    public function getEngineCode(): EngineInterface
    {
        throw new NotFoundException();
    }

    /** @throws void */
    #[Override]
    public function hasEngineVersion(): bool
    {
        return false;
    }

    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getEngineVersion(string | null $code = null): VersionInterface
    {
        return new NullVersion();
    }
}
