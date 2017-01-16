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
 * WURFL related utilities
 */
class Utils
{
    /**
     * @var array
     */
    private static $userAgentSearchOrder = [
        Constants::HEADER_DEVICE_STOCK_UA    => 'device',
        Constants::HEADER_DEVICE_UA          => 'device',
        Constants::HEADER_SKYFIRE_VERSION    => 'browser',
        Constants::HEADER_BLUECOAT_VIA       => 'browser',
        Constants::HEADER_OPERAMINI_PHONE_UA => 'browser',
        Constants::HEADER_UCBROWSER_UA       => 'browser',
        Constants::HEADER_HTTP_USERAGENT     => 'generic',
    ];

    /**
     * returns the User Agent From $request or empty string if not found
     *
     * @param array $request                     HTTP Request array (normally $_SERVER)
     * @param bool  $overrideSideloadedBrowserUa
     *
     * @return string|null
     */
    public static function getUserAgent(array $request, $overrideSideloadedBrowserUa = true)
    {
        if (!$overrideSideloadedBrowserUa && isset($request[Constants::HEADER_HTTP_USERAGENT])) {
            return $request[Constants::HEADER_HTTP_USERAGENT];
        }

        if (isset($request[Constants::UA])) {
            return $request[Constants::UA];
        }

        foreach (array_keys(self::$userAgentSearchOrder) as $header) {
            if (isset($request[$header])) {
                return $request[$header];
            }
        }

        return null;
    }

    /**
     * returns the User Agent From $request or empty string if not found
     *
     * @param array $request HTTP Request array (normally $_SERVER)
     *
     * @return string|null
     */
    public static function getDeviceUserAgent(array $request)
    {
        foreach (self::$userAgentSearchOrder as $header => $type) {
            if (!in_array($type, ['device', 'generic'])) {
                continue;
            }

            if (isset($request[$header])) {
                return $request[$header];
            }
        }

        return null;
    }

    /**
     * returns the User Agent From $request or empty string if not found
     *
     * @param array $request HTTP Request array (normally $_SERVER)
     *
     * @return string|null
     */
    public static function getBrowserUserAgent(array $request)
    {
        foreach (self::$userAgentSearchOrder as $header => $type) {
            if (!in_array($type, ['browser', 'generic'])) {
                continue;
            }

            if (isset($request[$header])) {
                return $request[$header];
            }
        }

        return null;
    }

    /**
     * Returns the UA Profile from the $request
     *
     * @param array $request HTTP Request array (normally $_SERVER)
     *
     * @return string|null UAProf URL
     */
    public static function getUserAgentProfile(array $request)
    {
        if (isset($request[Constants::HEADER_WAP_PROFILE])) {
            return $request[Constants::HEADER_WAP_PROFILE];
        }

        if (isset($request[Constants::HEADER_PROFILE])) {
            return $request[Constants::HEADER_PROFILE];
        }

        if (isset($request[Constants::HEADER_OPT])) {
            $opt              = $request[Constants::HEADER_OPT];
            $regex            = '/ns=\\d+/';
            $matches          = [];
            $namespaceProfile = null;

            if (preg_match($regex, $opt, $matches)) {
                $namespaceProfile = substr($matches[0], 2) . '-Profile';
            }

            if ($namespaceProfile !== null && isset($request[$namespaceProfile])) {
                return $request[$namespaceProfile];
            }
        }

        return null;
    }

    /**
     * Checks if the requester device is xhtml enabled
     *
     * @param array $request HTTP Request array (normally $_SERVER)
     *
     * @return bool
     */
    public static function isXhtmlRequester(array $request)
    {
        if (!isset($request[Constants::ACCEPT_HEADER_NAME])) {
            return false;
        }

        $accept = $request[Constants::ACCEPT_HEADER_NAME];

        if ((strpos($accept, Constants::ACCEPT_HEADER_VND_WAP_XHTML_XML) !== false)
            || (strpos($accept, Constants::ACCEPT_HEADER_XHTML_XML) !== false)
            || (strpos($accept, Constants::ACCEPT_HEADER_TEXT_HTML) !== false)
        ) {
            return true;
        }

        return false;
    }
}
