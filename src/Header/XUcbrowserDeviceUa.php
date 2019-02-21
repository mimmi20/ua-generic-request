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

final class XUcbrowserDeviceUa implements HeaderInterface
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
        if ('?' === $this->value) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function hasBrowserInfo(): bool
    {
        if (preg_match('/msie|dorado|opera|safari|obigo|netfront|s40ovibrowser|dolfin|opera|blackberry/i', mb_strtolower($this->value))) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasPlatformInfo(): bool
    {
        if (preg_match('/bada|android|blackberry|brew|iphone|mre|windows|mtk|symbian/i', mb_strtolower($this->value))) {
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
