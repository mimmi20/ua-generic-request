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
        $utils = new Utils($request);

        return new GenericRequest(
            $request,
            $utils->getUserAgent($overrideSideloadedBrowserUa),
            $utils->getUserAgentProfile(),
            $utils->isXhtmlRequester(),
            $utils->getBrowserUserAgent(),
            $utils->getDeviceUserAgent()
        );
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
        $request = [Constants::HEADER_HTTP_USERAGENT => $userAgent];

        return new GenericRequest($request, $userAgent);
    }

    /**
     * @param array $data
     *
     * @return \Wurfl\Request\GenericRequest
     */
    public function fromArray(array $data)
    {
        if (isset($data['userAgent'])) {
            $userAgent = $data['userAgent'];
        } else {
            $userAgent = '';
        }

        if (isset($data['request']) && is_array($data['request'])) {
            $request = $data['request'];
        } else {
            $request = [Constants::HEADER_HTTP_USERAGENT => $userAgent];
        }

        if (isset($data['browserUserAgent'])) {
            $browserUa = $data['browserUserAgent'];
        } else {
            $browserUa = null;
        }

        if (isset($data['deviceUserAgent'])) {
            $deviceUa = $data['deviceUserAgent'];
        } else {
            $deviceUa = null;
        }

        if (isset($data['userAgentProfile'])) {
            $profile = $data['userAgentProfile'];
        } else {
            $profile = null;
        }

        if (isset($data['xhtmlDevice'])) {
            $xhtml = $data['xhtmlDevice'];
        } else {
            $xhtml = false;
        }

        return new GenericRequest($request, $userAgent, $profile, $xhtml, $browserUa, $deviceUa);
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
