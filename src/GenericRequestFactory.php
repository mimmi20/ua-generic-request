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

use Psr\Http\Message\MessageInterface;

/**
 * Creates a Generic WURFL Request from the raw HTTP Request
 */
class GenericRequestFactory
{
    /**
     * Creates Generic Request from the given HTTP Request (normally $_SERVER).
     *
     * @param array $headers HTTP Request
     *
     * @return \UaRequest\GenericRequest
     */
    public function createRequestFromArray(array $headers): GenericRequest
    {
        return new GenericRequest($headers);
    }

    /**
     * Create a Generic Request from the given $userAgent
     *
     * @param string $userAgent
     *
     * @return \UaRequest\GenericRequest
     */
    public function createRequestFromString(string $userAgent): GenericRequest
    {
        return new GenericRequest([Constants::HEADER_HTTP_USERAGENT => $userAgent]);
    }

    /**
     * Create a Generic Request from a given PSR-7 HTTP message
     *
     * @param \Psr\Http\Message\MessageInterface $message
     *
     * @return \UaRequest\GenericRequest
     */
    public function createRequestFromPsr7Message(MessageInterface $message): GenericRequest
    {
        $headers = [];

        foreach ($message->getHeaders() as $name => $values) {
            $headers[$name] = implode(', ', $values);
        }

        return new GenericRequest($headers);
    }
}
