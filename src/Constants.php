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
 * WURFL PHP API Constants
 *
 * @package    WURFL
 */
class Constants
{
    const ACCEPT_HEADER_NAME              = 'accept';
    const ACCEPT_HEADER_VND_WAP_XHTML_XML = 'application/vnd.wap.xhtml+xml';
    const ACCEPT_HEADER_XHTML_XML         = 'application/xhtml+xml';
    const ACCEPT_HEADER_TEXT_HTML         = 'application/text+html';

    const HEADER_WAP_PROFILE = 'HTTP_X_WAP_PROFILE';
    const HEADER_PROFILE     = 'HTTP_PROFILE';
    const HEADER_OPT         = 'Opt';

    const HEADER_HTTP_USERAGENT     = 'HTTP_USER_AGENT';
    const HEADER_DEVICE_STOCK_UA    = 'HTTP_DEVICE_STOCK_UA';
    const HEADER_DEVICE_UA          = 'HTTP_X_DEVICE_USER_AGENT';
    const HEADER_SKYFIRE_VERSION    = 'HTTP_X_SKYFIRE_VERSION';
    const HEADER_BLUECOAT_VIA       = 'HTTP_X_BLUECOAT_VIA';
    const HEADER_OPERAMINI_PHONE_UA = 'HTTP_X_OPERAMINI_PHONE_UA';
    const HEADER_UCBROWSER_UA       = 'HTTP_X_UCBROWSER_UA';

    const UA       = 'UA';
    const NO_MATCH = null;
}
