<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2020, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace UaRequest\Header;

interface HeaderInterface
{
    /**
     * Retrieve header value
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * @return bool
     */
    public function hasDeviceInfo(): bool;

    /**
     * @return bool
     */
    public function hasBrowserInfo(): bool;

    /**
     * @return bool
     */
    public function hasPlatformInfo(): bool;

    /**
     * @return bool
     */
    public function hasEngineInfo(): bool;
}
