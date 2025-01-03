<?php

/**
 * This file is part of the mimmi20/ua-generic-request package.
 *
 * Copyright (c) 2015-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequest;

/**
 * API Constants
 */
final class Constants
{
    /** @api */
    public const string HEADER_HTTP_USERAGENT = 'HTTP_USER_AGENT';

    /** @api */
    public const string HEADER_USERAGENT = 'user-agent';

    /** @api */
    public const string HEADER_DEVICE_STOCK_UA = 'device-stock-ua';

    /** @api */
    public const string HEADER_DEVICE_UA = 'x-device-user-agent';

    /** @api */
    public const string HEADER_SKYFIRE_VERSION = 'x-skyfire-version';

    /** @api */
    public const string HEADER_SKYFIRE_PHONE = 'x-skyfire-phone';

    /** @api */
    public const string HEADER_BLUECOAT_VIA = 'x-bluecoat-via';

    /** @api */
    public const string HEADER_OPERAMINI_PHONE_UA = 'x-operamini-phone-ua';

    /** @api */
    public const string HEADER_OPERAMINI_PHONE = 'x-operamini-phone';

    /** @api */
    public const string HEADER_UCBROWSER_UA = 'x-ucbrowser-ua';

    /** @api */
    public const string HEADER_UCBROWSER_DEVICE_UA = 'x-ucbrowser-device-ua';

    /** @api */
    public const string HEADER_UCBROWSER_DEVICE = 'x-ucbrowser-device';

    /** @api */
    public const string HEADER_UCBROWSER_PHONE_UA = 'x-ucbrowser-phone-ua';

    /** @api */
    public const string HEADER_UCBROWSER_PHONE = 'x-ucbrowser-phone';

    /** @api */
    public const string HEADER_ORIGINAL_UA = 'x-original-user-agent';

    /** @api */
    public const string HEADER_BOLT_PHONE_UA = 'x-bolt-phone-ua';

    /** @api */
    public const string HEADER_MOBILE_UA = 'x-mobile-ua';

    /** @api */
    public const string HEADER_REQUESTED_WITH = 'x-requested-with';

    /** @api */
    public const string HEADER_UA_OS = 'ua-os';

    /** @api */
    public const string HEADER_BAIDU_FLYFLOW = 'baidu-flyflow';

    /** @api */
    public const string HEADER_WAP_PROFILE = 'x-wap-profile';

    /** @api */
    public const string HEADER_PUFFIN_UA = 'x-puffin-ua';

    /** @api */
    public const string HEADER_MOBILE_GATEWAY = 'x-mobile-gateway';

    /** @api */
    public const string HEADER_NB_CONTENT = 'x-nb-content';

    /** @api */
    public const string HEADER_SEC_CH_UA = 'Sec-CH-UA';

    /** @api */
    public const string HEADER_SEC_CH_UA_ARCH = 'Sec-CH-UA-Arch';

    /** @api */
    public const string HEADER_SEC_CH_UA_BITNESS = 'Sec-CH-UA-Bitness';

    /** @api */
    public const string HEADER_SEC_CH_UA_FULL_VERSION = 'Sec-CH-UA-Full-Version';

    /** @api */
    public const string HEADER_SEC_CH_UA_FULL_VERSION_LIST = 'Sec-CH-UA-Full-Version-List';

    /** @api */
    public const string HEADER_SEC_CH_UA_MOBILE = 'Sec-CH-UA-Mobile';

    /** @api */
    public const string HEADER_SEC_CH_UA_MODEL = 'Sec-CH-UA-Model';

    /** @api */
    public const string HEADER_SEC_CH_UA_PLATFORM = 'Sec-CH-UA-Platform';

    /** @api */
    public const string HEADER_SEC_CH_UA_PLATFORM_VERSION = 'Sec-CH-UA-Platform-Version';

    /** @api */
    public const string HEADER_TEST = 'unknown header';
}
