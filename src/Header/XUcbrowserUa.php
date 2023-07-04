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

use function preg_match;

final class XUcbrowserUa implements HeaderInterface
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
        $matches = [];

        if (!preg_match('/dv\((?P<device>[^\)]+)\);/', $this->value, $matches)) {
            return false;
        }

        return $matches['device'] !== 'j2me' && $matches['device'] !== 'Opera';
    }

    /** @throws void */
    public function hasBrowserInfo(): bool
    {
        return (bool) preg_match('/pr\((?P<browser>[^\)]+)\);/', $this->value);
    }

    /** @throws void */
    public function hasPlatformInfo(): bool
    {
        if (preg_match('/ov\((?P<platform>[\d_\.]+)\);/', $this->value)) {
            return false;
        }

        return (bool) preg_match('/ov\((?P<platform>[^\)]+)\);/', $this->value);
    }

    /** @throws void */
    public function hasEngineInfo(): bool
    {
        return (bool) preg_match('/re\((?P<engine>[^\)]+)\)/', $this->value);
    }
}
