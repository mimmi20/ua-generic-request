<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2019, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace UaRequest\Header;

final class DeviceStockUa implements HeaderInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * Useragent constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Retrieve header value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function hasDeviceInfo(): bool
    {
        if (0 < preg_match('/samsung|nokia|blackberry|smartfren|sprint|iphone|lava|gionee|philips|htc|mi 2sc/i', mb_strtolower($this->value))) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasBrowserInfo(): bool
    {
        if (0 < preg_match('/opera mini/i', mb_strtolower($this->value))) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasPlatformInfo(): bool
    {
        if (0 < preg_match('/bada|android|blackberry|brew|iphone|mre|windows|mtk|mre/i', mb_strtolower($this->value))) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasEngineInfo(): bool
    {
        if (0 < preg_match('/trident|presto|webkit|gecko/i', mb_strtolower($this->value))) {
            return true;
        }

        return false;
    }
}
