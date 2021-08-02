<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2021, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequest\Header;

use UaRequest\Constants;
use UaRequest\NotFoundException;

use function array_key_exists;

final class HeaderLoader implements HeaderLoaderInterface
{
    private const OPTIONS = [
        Constants::HEADER_BAIDU_FLYFLOW => BaiduFlyflow::class,
        Constants::HEADER_DEVICE_STOCK_UA => DeviceStockUa::class,
        Constants::HEADER_UA_OS => UaOs::class,
        Constants::HEADER_USERAGENT => Useragent::class,
        Constants::HEADER_DEVICE_UA => XDeviceUseragent::class,
        Constants::HEADER_OPERAMINI_PHONE => XOperaminiPhone::class,
        Constants::HEADER_OPERAMINI_PHONE_UA => XOperaminiPhoneUa::class,
        Constants::HEADER_ORIGINAL_UA => XOriginalUseragent::class,
        Constants::HEADER_PUFFIN_UA => XPuffinUa::class,
        Constants::HEADER_REQUESTED_WITH => XRequestedWith::class,
        Constants::HEADER_UCBROWSER_DEVICE => XUcbrowserDevice::class,
        Constants::HEADER_UCBROWSER_DEVICE_UA => XUcbrowserDeviceUa::class,
        Constants::HEADER_UCBROWSER_PHONE => XUcbrowserPhone::class,
        Constants::HEADER_UCBROWSER_PHONE_UA => XUcbrowserPhoneUa::class,
        Constants::HEADER_UCBROWSER_UA => XUcbrowserUa::class,
    ];

    public function has(string $key): bool
    {
        return array_key_exists($key, self::OPTIONS);
    }

    /**
     * @throws NotFoundException
     */
    public function load(string $key, ?string $value = null): HeaderInterface
    {
        if (!$this->has($key)) {
            throw new NotFoundException('the header with name "' . $key . '" was not found');
        }

        $class = self::OPTIONS[$key];

        return new $class($value);
    }
}
