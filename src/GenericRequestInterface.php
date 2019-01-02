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
namespace UaRequest;

interface GenericRequestInterface
{
    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @return array
     */
    public function getFilteredHeaders(): array;

    /**
     * @return string
     */
    public function getBrowserUserAgent(): string;

    /**
     * @return string
     */
    public function getDeviceUserAgent(): string;
}
