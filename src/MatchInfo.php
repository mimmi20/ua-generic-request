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
 * Information about the matching process
 */
class MatchInfo implements \Serializable
{
    /**
     * @var bool Response was returned from cache
     */
    public $fromCache = false;

    /**
     * @var string The type of match that was made
     */
    public $matchType;

    /**
     * @var string The responsible Matcher/Handler
     */
    public $matcher;

    /**
     * @var string The history of Matchers/Handlers
     */
    public $matcherHistory = '';

    /**
     * @var float The time it took to lookup the user agent
     */
    public $lookupTime;

    /**
     * @var string The user agent after normalization
     */
    public $normalizedUserAgent;

    /**
     * @var string The user agent after cleaning
     */
    public $cleanedUserAgent;

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     *
     * @link http://php.net/manual/en/serializable.serialize.php
     *
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(
            array(
                'fromCache'           => $this->fromCache,
                'matchType'           => $this->matchType,
                'matcher'             => $this->matcher,
                'matcherHistory'      => $this->matcherHistory,
                'lookupTime'          => $this->lookupTime,
                'normalizedUserAgent' => $this->normalizedUserAgent,
                'cleanedUserAgent'    => $this->cleanedUserAgent,
            )
        );
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     */
    public function unserialize($serialized)
    {
        $unseriliazedData = unserialize($serialized);

        $this->fromCache           = $unseriliazedData['fromCache'];
        $this->matchType           = $unseriliazedData['matchType'];
        $this->matcher             = $unseriliazedData['matcher'];
        $this->matcherHistory      = $unseriliazedData['matcherHistory'];
        $this->lookupTime          = $unseriliazedData['lookupTime'];
        $this->normalizedUserAgent = $unseriliazedData['normalizedUserAgent'];
        $this->cleanedUserAgent    = $unseriliazedData['cleanedUserAgent'];
    }
}
