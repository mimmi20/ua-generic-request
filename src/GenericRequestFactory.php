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
use Zend\Diactoros\ServerRequestFactory;

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
        $upperCaseHeaders = [];

        foreach ($headers as $header => $value) {
            $upperCaseHeader = mb_strtoupper(str_replace('-', '_', $header));

            if (0 !== mb_strpos($upperCaseHeader, 'HTTP_')) {
                $upperCaseHeader = 'HTTP_' . $upperCaseHeader;
            }

            $upperCaseHeaders[$upperCaseHeader] = $value;
        }

        $message = ServerRequestFactory::fromGlobals($upperCaseHeaders);

        return new GenericRequest($message);
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
        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => $userAgent]);

        return new GenericRequest($message);
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
        return new GenericRequest($message);
    }
}
