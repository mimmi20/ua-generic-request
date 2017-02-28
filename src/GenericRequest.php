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
 * Generic WURFL Request object containing User Agent, UAProf and xhtml device data; its id
 * property is the SHA512 hash of the user agent
 */
class GenericRequest
{
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
    private $browserUserAgent;

    /**
     * @var string
     */
    private $deviceUserAgent;

    /**
     * @var null|string
     */
    private $userAgentProfile;

    /**
     * @var bool
     */
    private $xhtmlDevice;

    /**
     * @var string
     */
    private $id;

    /**
     * @param array $request                     Original HTTP headers
     * @param bool  $overrideSideloadedBrowserUa
     */
    public function __construct(array $request, $overrideSideloadedBrowserUa = true)
    {
        $this->request = $request;

        $utils = new Utils($request);

        $this->userAgent        = $utils->getUserAgent($overrideSideloadedBrowserUa);
        $this->userAgentProfile = $utils->getUserAgentProfile();
        $this->xhtmlDevice      = $utils->isXhtmlRequester();
        $this->browserUserAgent = $utils->getBrowserUserAgent();
        $this->deviceUserAgent  = $utils->getDeviceUserAgent();
        $this->id               = hash('sha512', $this->userAgent);
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
    public function getBrowserUserAgent()
    {
        return $this->browserUserAgent;
    }

    /**
     * @return string
     */
    public function getDeviceUserAgent()
    {
        return $this->deviceUserAgent;
    }

    /**
     * @return string
     */
    public function getUserAgentProfile()
    {
        return $this->userAgentProfile;
    }

    /**
     * @return bool
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
     * Get the original HTTP header value from the request
     *
     * @param string $name
     *
     * @return string
     */
    public function getOriginalHeader($name)
    {
        if ($this->originalHeaderExists($name)) {
            return $this->request[$name];
        }
    }

    /**
     * Checks if the original HTTP header is set in the request
     *
     * @param string $name
     *
     * @return bool
     */
    public function originalHeaderExists($name)
    {
        return array_key_exists($name, $this->request);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'request'                => $this->request,
            'userAgent'              => $this->userAgent,
            'browserUserAgent'       => $this->browserUserAgent,
            'deviceUserAgent'        => $this->deviceUserAgent,
            'userAgentProfile'       => $this->userAgentProfile,
            'xhtmlDevice'            => $this->xhtmlDevice,
            'id'                     => $this->id,
        ];
    }
}
