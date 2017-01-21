<?php
/**
 * Copyright (c) 2015 ScientiaMobile, Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * Refer to the COPYING.txt file distributed with this package.
 *
 *
 * @category   WURFL
 *
 * @copyright  ScientiaMobile, Inc.
 * @license    GNU Affero General Public License
 */

namespace Wurfl\Request;

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
     */
    public function createRequest(array $request, $overrideSideloadedBrowserUa = true)
    {
        return new GenericRequest($request, $overrideSideloadedBrowserUa);
    }

    /**
     * Create a Generic Request from the given $userAgent
     *
     * @param string $userAgent
     *
     * @return \Wurfl\Request\GenericRequest
     */
    public function createRequestForUserAgent($userAgent)
    {
        return new GenericRequest([Constants::HEADER_HTTP_USERAGENT => $userAgent]);
    }

    /**
     * @param array $data
     *
     * @return \Wurfl\Request\GenericRequest
     */
    public function fromArray(array $data)
    {
        if (isset($data['request'])) {
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

    /**
     * @param string $json
     *
     * @return \Wurfl\Request\GenericRequest
     */
    public function fromJson($json)
    {
        return $this->fromArray((array) json_decode($json));
    }
}
