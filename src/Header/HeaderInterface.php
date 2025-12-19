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
use UaData\EngineInterface;
use UaData\OsInterface;
use UaRequest\Exception\NotFoundException;
use UaResult\Bits\Bits;
use UaResult\Device\Architecture;
use UaResult\Device\FormFactor;

interface HeaderInterface
{
    /**
     * Retrieve header value
     *
     * @throws void
     */
    public function getValue(): string;

    /**
     * Retrieve normalized header value
     *
     * @throws void
     */
    public function getNormalizedValue(): string;

    /** @throws void */
    public function hasDeviceArchitecture(): bool;

    /** @throws void */
    public function getDeviceArchitecture(): Architecture;

    /** @throws void */
    public function hasDeviceFormFactor(): bool;

    /**
     * @return list<FormFactor>
     *
     * @throws void
     */
    public function getDeviceFormFactor(): array;

    /** @throws void */
    public function hasDeviceBitness(): bool;

    /** @throws void */
    public function getDeviceBitness(): Bits;

    /** @throws void */
    public function hasDeviceIsMobile(): bool;

    /** @throws void */
    public function getDeviceIsMobile(): bool | null;

    /** @throws void */
    public function hasDeviceCode(): bool;

    /** @throws void */
    public function getDeviceCode(): string | null;

    /** @throws void */
    public function hasDeviceIsWow64(): bool;

    /** @throws void */
    public function getDeviceIsWow64(): bool | null;

    /** @throws void */
    public function hasClientCode(): bool;

    /** @throws void */
    public function getClientCode(): string | null;

    /** @throws void */
    public function hasClientVersion(): bool;

    /** @throws void */
    public function getClientVersion(string | null $code = null): VersionInterface;

    /** @throws void */
    public function hasPlatformCode(): bool;

    /** @throws NotFoundException */
    public function getPlatformCode(string | null $derivate = null): OsInterface;

    /** @throws void */
    public function hasPlatformVersion(): bool;

    /** @throws void */
    public function getPlatformVersion(string | null $code = null): VersionInterface;

    /** @throws void */
    public function hasEngineCode(): bool;

    /** @throws NotFoundException */
    public function getEngineCode(): EngineInterface;

    /** @throws void */
    public function hasEngineVersion(): bool;

    /** @throws void */
    public function getEngineVersion(string | null $code = null): VersionInterface;
}
