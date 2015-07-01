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
 * @package    WURFL
 * @copyright  ScientiaMobile, Inc.
 * @license    GNU Affero General Public License
 */

namespace Wurfl\Request;

/**
 * Generic WURFL Request object containing User Agent, UAProf and xhtml device data; its id
 * property is the SHA512 hash of the user agent
 *
 * @package WURFL_Request
 */
class GenericRequest
{
    const MAX_HTTP_HEADER_LENGTH = 512;

    /**
     * @var array
     */
    private $request;

    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var string
     */
    private $userAgentNormalized;

    /**
     * @var null|string
     */
    private $userAgentProfile;

    /**
     * @var boolean
     */
    private $xhtmlDevice;

    /**
     * @var string
     */
    private $id;

    /**
     * @var \Wurfl\Request\MatchInfo
     */
    private $matchInfo;

    /**
     * @var array
     */
    private $userAgentsWithDeviceID;

    /**
     * @param array  $request Original HTTP headers
     * @param string $userAgent
     * @param string $userAgentProfile
     * @param boolean $xhtmlDevice
     */
    public function __construct(array $request, $userAgent, $userAgentProfile = null, $xhtmlDevice = false)
    {
        $this->request                = $this->sanitizeHeaders($request);
        $this->userAgent              = $this->sanitizeHeaders($userAgent);
        $this->userAgentProfile       = $this->sanitizeHeaders($userAgentProfile);
        $this->xhtmlDevice            = $xhtmlDevice;
        $this->id                     = hash('sha512', $userAgent);
        $this->matchInfo              = new MatchInfo();
        $this->userAgentsWithDeviceID = array();
        $this->userAgentNormalized    = $this->userAgent;
    }

    /**
     * @param array|string $headers
     *
     * @return array|string
     */
    private function sanitizeHeaders($headers)
    {
        if (!is_array($headers)) {
            return $this->truncateHeader($headers);
        }

        foreach ($headers as $header => $value) {
            $headers[$header] = $this->truncateHeader($value);
        }

        return $headers;
    }

    /**
     * @return array
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    public function getUserAgentNormalized()
    {
        return $this->userAgentNormalized;
    }

    /**
     * @param string $userAgentNormalized
     */
    public function setUserAgentNormalized($userAgentNormalized)
    {
        $this->userAgentNormalized = $userAgentNormalized;
    }

    /**
     * @return string
     */
    public function getUserAgentProfile()
    {
        return $this->userAgentProfile;
    }

    /**
     * @return boolean
     */
    public function isXhtmlDevice()
    {
        return $this->xhtmlDevice;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Wurfl\Request\MatchInfo
     */
    public function getMatchInfo()
    {
        return $this->matchInfo;
    }

    /**
     * @return array
     */
    public function getUserAgentsWithDeviceID()
    {
        return $this->userAgentsWithDeviceID;
    }

    /**
     * @param array $userAgentsWithDeviceID
     */
    public function setUserAgentsWithDeviceID(array $userAgentsWithDeviceID)
    {
        $this->userAgentsWithDeviceID = $userAgentsWithDeviceID;
    }

    /**
     * @param string $header
     *
     * @return string
     */
    private function truncateHeader($header)
    {
        if (strpos($header, 'HTTP_') !== 0 || strlen($header) <= self::MAX_HTTP_HEADER_LENGTH) {
            return $header;
        }

        return substr($header, 0, self::MAX_HTTP_HEADER_LENGTH);
    }

    /**
     * Get the original HTTP header value from the request
     *
     * @param string $name
     *
     * @return string
     */
    public function getOriginalHeader($name)
    {
        return array_key_exists($name, $this->request) ? $this->request[$name] : null;
    }

    /**
     * Checks if the original HTTP header is set in the request
     *
     * @param string $name
     *
     * @return boolean
     */
    public function originalHeaderExists($name)
    {
        return array_key_exists($name, $this->request);
    }
}
