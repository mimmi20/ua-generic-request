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

final class XOperaminiPhoneUa implements HeaderInterface
{
    /** @var string */
    private $value;

    /**
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

        return 0 < preg_match('/samsung|nokia|blackberry|smartfren|sprint|iphone|lava|gionee|philips|htc|pantech|lg|casio|zte|mi 2sc/i', mb_strtolower($this->value));
    }

    /**
     * @return bool
     */
    public function hasBrowserInfo(): bool
    {
        return 0 < preg_match('/opera mini/i', mb_strtolower($this->value));
    }

    /**
     * @return bool
     */
    public function hasPlatformInfo(): bool
    {
        return 0 < preg_match('/bada|android|blackberry|brew|iphone|mre|windows|mtk|mre/i', mb_strtolower($this->value));
    }

    /**
     * @return bool
     */
    public function hasEngineInfo(): bool
    {
        return 0 < preg_match('/trident|presto|webkit|gecko/i', mb_strtolower($this->value));
    }
}
