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

final class XUcbrowserDeviceUa implements HeaderInterface
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
        return '?' !== $this->value;
    }

    /**
     * @return bool
     */
    public function hasBrowserInfo(): bool
    {
        return (bool) preg_match('/msie|dorado|safari|obigo|netfront|s40ovibrowser|dolfin|(?<!browser\/)opera(?!\/9\.80| mobi)|blackberry/i', mb_strtolower($this->value));
    }

    /**
     * @return bool
     */
    public function hasPlatformInfo(): bool
    {
        return (bool) preg_match('/bada|android|blackberry|brew|iphone|mre|windows|mtk|symbian|mre/i', mb_strtolower($this->value));
    }

    /**
     * @return bool
     */
    public function hasEngineInfo(): bool
    {
        return false;
    }
}
