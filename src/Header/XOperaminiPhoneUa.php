<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2023, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequest\Header;

use function mb_strtolower;
use function preg_match;

final class XOperaminiPhoneUa implements HeaderInterface
{
    /** @throws void */
    public function __construct(private readonly string $value)
    {
        // nothing to do
    }

    /**
     * Retrieve header value
     *
     * @throws void
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /** @throws void */
    public function hasDeviceInfo(): bool
    {
        if (mb_strtolower($this->value) === 'motorola') {
            return false;
        }

        return (bool) preg_match(
            '/samsung|nokia|blackberry|smartfren|sprint|iphone|lava|gionee|philips|htc|pantech|lg|casio|zte|mi 2sc/i',
            $this->value,
        );
    }

    /** @throws void */
    public function hasBrowserInfo(): bool
    {
        return (bool) preg_match('/opera mini/i', $this->value);
    }

    /** @throws void */
    public function hasPlatformInfo(): bool
    {
        return (bool) preg_match(
            '/bada|android|blackberry|brew|iphone|mre|windows|mtk/i',
            $this->value,
        );
    }

    /** @throws void */
    public function hasEngineInfo(): bool
    {
        return (bool) preg_match('/trident|presto|webkit|gecko/i', $this->value);
    }
}
