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

/**
 * WURFL related utilities
 */
class Utils
{
    /**
     * @var array
     */
    private $userAgentSearchOrder = [
        Constants::HEADER_DEVICE_STOCK_UA     => 'device',
        Constants::HEADER_DEVICE_UA           => 'device',
        Constants::HEADER_UCBROWSER_DEVICE_UA => 'device',
        Constants::HEADER_SKYFIRE_PHONE       => 'device',
        Constants::HEADER_SKYFIRE_VERSION     => 'browser',
        Constants::HEADER_BLUECOAT_VIA        => 'browser',
        Constants::HEADER_OPERAMINI_PHONE_UA  => 'browser',
        Constants::HEADER_BOLT_PHONE_UA       => 'browser',
        Constants::HEADER_UCBROWSER_UA        => 'browser',
        Constants::HEADER_MOBILE_UA           => 'browser',
        Constants::HEADER_ORIGINAL_UA         => 'generic',
        Constants::HEADER_HTTP_USERAGENT      => 'generic',
    ];

    /**
     * @var array
     */
    private $request = [];

    /**
     * @param array $request
     */
    public function __construct(array $request = [])
    {
        $this->request = $request;
    }

    /**
     * returns the User Agent or empty string if not found
     *
     * @param bool $overrideSideloadedBrowserUa
     *
     * @return string
     */
    public function getUserAgent($overrideSideloadedBrowserUa = true)
    {
        if (!$overrideSideloadedBrowserUa && isset($this->request[Constants::HEADER_HTTP_USERAGENT])) {
            return $this->request[Constants::HEADER_HTTP_USERAGENT];
        }

        if (isset($this->request[Constants::UA])) {
            return $this->request[Constants::UA];
        }

        foreach (array_keys($this->userAgentSearchOrder) as $header) {
            if (isset($this->request[$header])) {
                return $this->request[$header];
            }
        }

        return '';
    }

    /**
     * returns the User Agent or empty string if not found
     *
     * @return string
     */
    public function getDeviceUserAgent()
    {
        foreach ($this->userAgentSearchOrder as $header => $type) {
            if (!in_array($type, ['device', 'generic'])) {
                continue;
            }

            if (isset($this->request[$header])) {
                return $this->request[$header];
            }
        }

        return '';
    }

    /**
     * returns the User Agent or empty string if not found
     *
     * @return string
     */
    public function getBrowserUserAgent()
    {
        foreach ($this->userAgentSearchOrder as $header => $type) {
            if (!in_array($type, ['browser', 'generic'])) {
                continue;
            }

            if (isset($this->request[$header])) {
                return $this->request[$header];
            }
        }

        return '';
    }

    /**
     * Returns the UA Profile
     *
     * @return string UAProf URL
     */
    public function getUserAgentProfile()
    {
        if (isset($this->request[Constants::HEADER_WAP_PROFILE])) {
            return $this->request[Constants::HEADER_WAP_PROFILE];
        }

        if (isset($this->request[Constants::HEADER_PROFILE])) {
            return $this->request[Constants::HEADER_PROFILE];
        }

        if (isset($this->request[Constants::HEADER_OPT])) {
            $opt              = $this->request[Constants::HEADER_OPT];
            $regex            = '/ns=\\d+/';
            $matches          = [];
            $namespaceProfile = null;

            if (preg_match($regex, $opt, $matches)) {
                $namespaceProfile = mb_substr($matches[0], 2) . '-Profile';
            }

            if ($namespaceProfile !== null && isset($this->request[$namespaceProfile])) {
                return $this->request[$namespaceProfile];
            }
        }

        return '';
    }

    /**
     * Checks if the requester device is xhtml enabled
     *
     * @return bool
     */
    public function isXhtmlRequester()
    {
        if (!isset($this->request[Constants::ACCEPT_HEADER_NAME])) {
            return false;
        }

        $accept = $this->request[Constants::ACCEPT_HEADER_NAME];

        if ((mb_strpos($accept, Constants::ACCEPT_HEADER_VND_WAP_XHTML_XML) !== false)
            || (mb_strpos($accept, Constants::ACCEPT_HEADER_XHTML_XML) !== false)
            || (mb_strpos($accept, Constants::ACCEPT_HEADER_TEXT_HTML) !== false)
        ) {
            return true;
        }

        return false;
    }
}
