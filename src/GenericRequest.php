<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2018, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace UaRequest;

use UaRequest\GenericRequest\Utils;

class GenericRequest
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @param array $headers
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBrowserUserAgent(): string
    {
        return (new Utils($this->headers))->getBrowserUserAgent();
    }

    /**
     * @return string
     */
    public function getDeviceUserAgent(): string
    {
        return (new Utils($this->headers))->getDeviceUserAgent();
    }
}
