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

final class XOperaminiPhoneUa implements HeaderInterface
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
        if (in_array(mb_strtolower($this->value), ['mozilla/5.0 (bada 2.0.0)', 'motorola'], true)) {
            return false;
        }

        if ((bool) preg_match('/samsung|nokia|blackberry|smartfren|sprint|iphone|lava|gionee|philips|htc|pantech|lg|casio|zte|mi 2sc/i', mb_strtolower($this->value))) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasBrowserInfo(): bool
    {
        if ((bool) preg_match('/msie|dorado|opera|safari|obigo|netfront|s40ovibrowser|dolfin|opera|blackberry/i', mb_strtolower($this->value))) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasPlatformInfo(): bool
    {
        if ((bool) preg_match('/bada|android|blackberry|brew|iphone|mre|windows|mtk/i', mb_strtolower($this->value))) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasEngineInfo(): bool
    {
        return false;
    }
}
