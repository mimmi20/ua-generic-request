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

use UaRequest\Constants;

final class XUcbrowserUa implements HeaderInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * Useragent constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }


    /**
     * Retrieve header name
     *
     * @return string
     */
    public function getFieldName(): string
    {
        return Constants::HEADER_UCBROWSER_UA;
    }

    /**
     * Retrieve header value
     *
     * @return string
     */
    public function getFieldValue(): string
    {
        return $this->value;
    }

    public function hasDeviceInfo(): bool
    {
        $matches = [];

        if (!preg_match('/dv\(([^\)]+)\)/', $this->value, $matches)) {
            return false;
        }

        if ('j2me' === $matches[1]) {
            return false;
        }

        return true;
    }

    public function hasBrowserInfo(): bool
    {
        $matches = [];

        if (!preg_match('/pr\(([^\)]+)\)/', $this->value, $matches)) {
            return false;
        }

        return true;
    }

    public function hasPlatformInfo(): bool
    {
        $matches = [];

        if (!preg_match('/ov\(([^\)]+)\)/', $this->value, $matches)) {
            return false;
        }

        return true;
    }

    public function hasEngineInfo(): bool
    {
        $matches = [];

        if (!preg_match('/re\(([^\)]+)\)/', $this->value, $matches)) {
            return false;
        }

        return true;
    }
}
