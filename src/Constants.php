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
    public const string HEADER_REQUESTED_WITH = 'x-requested-with';

    /** @api */
    public const string HEADER_UA_OS = 'ua-os';

    /** @api */
    public const string HEADER_BAIDU_FLYFLOW = 'baidu-flyflow';

    /** @api */
    public const string HEADER_PUFFIN_UA = 'x-puffin-ua';

    /** @api */
    public const string HEADER_CRAWLED_BY = 'x-crawled-by';

    /** @api */
    public const string HEADER_SEC_CH_UA = 'sec-ch-ua';

    /** @api */
    public const string HEADER_SEC_CH_UA_ARCH = 'sec-ch-ua-arch';

    /** @api */
    public const string HEADER_SEC_CH_UA_BITNESS = 'sec-ch-ua-bitness';

    /** @api */
    public const string HEADER_SEC_CH_UA_FULL_VERSION = 'sec-ch-ua-full-version';

    /** @api */
    public const string HEADER_SEC_CH_UA_FULL_VERSION_LIST = 'sec-ch-ua-full-version-list';

    /** @api */
    public const string HEADER_SEC_CH_UA_MOBILE = 'sec-ch-ua-mobile';

    /** @api */
    public const string HEADER_SEC_CH_UA_MODEL = 'sec-ch-ua-model';

    /** @api */
    public const string HEADER_SEC_CH_UA_PLATFORM = 'sec-ch-ua-platform';

    /** @api */
    public const string HEADER_SEC_CH_UA_PLATFORM_VERSION = 'sec-ch-ua-platform-version';

    /** @api */
    public const string HEADER_TEST = 'unknown header';
}
