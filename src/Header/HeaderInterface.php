<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2019, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace UaRequest\Header;

/**
 * Interface for HTTP Header classes.
 */
interface HeaderInterface
{
    /**
     * Retrieve header name
     *
     * @return string
     */
    public function getFieldName(): string;

    /**
     * Retrieve header value
     *
     * @return string
     */
    public function getFieldValue(): string;

    /**
     * Cast to string
     *
     * Returns in form of "NAME: VALUE"
     *
     * @return string
     */
    public function toString(): string;

    public function hasDeviceInfo(): bool;

    public function hasBrowserInfo(): bool;

    public function hasPlatformInfo(): bool;

    public function hasEngineInfo(): bool;
}
