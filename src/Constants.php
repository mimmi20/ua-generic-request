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
 * WURFL PHP API Constants
 */
class Constants
{
    const ACCEPT_HEADER_NAME              = 'accept';
    const ACCEPT_HEADER_VND_WAP_XHTML_XML = 'application/vnd.wap.xhtml+xml';
    const ACCEPT_HEADER_XHTML_XML         = 'application/xhtml+xml';
    const ACCEPT_HEADER_TEXT_HTML         = 'application/text+html';
    const ACCEPT_HEADER_ENCODING          = 'HTTP_ACCEPT_ENCODING';

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

    const UA = 'UA';
}
