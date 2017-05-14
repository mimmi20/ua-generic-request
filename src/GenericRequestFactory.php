<?php
/**
 * This file is part of the wurfl-generic-request package.
 *
 * Copyright (c) 2015-2017, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace Wurfl\Request;

use Psr\Http\Message\MessageInterface;

/**
 * Creates a Generic WURFL Request from the raw HTTP Request
 */
class GenericRequestFactory
{
    /**
     * Creates Generic Request from the given HTTP Request (normally $_SERVER)
     *
     * @param array $request                     HTTP Request
     * @param bool  $overrideSideloadedBrowserUa
     *
     * @return \Wurfl\Request\GenericRequest
     *
     * @deprecated use {@see createRequestFromArray} instead
     */
    public function createRequest(array $request, $overrideSideloadedBrowserUa = true)
    {
        return $this->createRequestFromArray($request, $overrideSideloadedBrowserUa);
    }

    /**
     * Creates Generic Request from the given HTTP Request (normally $_SERVER)
     *
     * @param array $request                     HTTP Request
     * @param bool  $overrideSideloadedBrowserUa
     *
     * @return \Wurfl\Request\GenericRequest
     */
    public function createRequestFromArray(array $request, $overrideSideloadedBrowserUa = true)
    {
        return new GenericRequest($request, $overrideSideloadedBrowserUa);
    }

    /**
     * Create a Generic Request from the given $userAgent
     *
     * @param string $userAgent
     *
     * @return \Wurfl\Request\GenericRequest
     *
     * @deprecated use {@see createRequestFromString} instead
     */
    public function createRequestForUserAgent($userAgent)
    {
        return $this->createRequestFromString($userAgent);
    }

    /**
     * Create a Generic Request from the given $userAgent
     *
     * @param string $userAgent
     *
     * @return \Wurfl\Request\GenericRequest
     */
    public function createRequestFromString($userAgent)
    {
        return new GenericRequest([Constants::HEADER_HTTP_USERAGENT => $userAgent]);
    }

    /**
     * Create a Generic Request from the given $userAgent
     *
     * @param \Psr\Http\Message\MessageInterface $message
     *
     * @return \Wurfl\Request\GenericRequest
     */
    public function createRequestFromPsr7Message(MessageInterface $message)
    {
        $headers = [];

        foreach ($message->getHeaders() as $name => $values) {
            $headers[$name] = implode(', ', $values);
        }

        return new GenericRequest($headers);
    }

    /**
     * @param array $data
     *
     * @return \Wurfl\Request\GenericRequest
     */
    public function fromArray(array $data)
    {
        if (isset($data[Constants::HEADER_HTTP_USERAGENT])) {
            $request = $data;
        } elseif (isset($data['headers'])) {
            $request = (array) $data['headers'];
        } elseif (isset($data['request'])) {
            $request = (array) $data['request'];
        } else {
            if (isset($data['userAgent'])) {
                $userAgent = $data['userAgent'];
            } else {
                $userAgent = '';
            }

            $request = [Constants::HEADER_HTTP_USERAGENT => $userAgent];
        }

        return new GenericRequest($request);
    }
}
