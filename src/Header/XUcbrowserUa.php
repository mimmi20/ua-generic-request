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

use function preg_match;

final class XUcbrowserUa implements HeaderInterface
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Retrieve header value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function hasDeviceInfo(): bool
    {
        $matches = [];

        if (!preg_match('/dv\((?P<device>[^\)]+)\);/', $this->value, $matches)) {
            return false;
        }

        return 'j2me' !== $matches['device'] && 'Opera' !== $matches['device'];
    }

    public function hasBrowserInfo(): bool
    {
        return (bool) preg_match('/pr\((?P<browser>[^\)]+)\);/', $this->value);
    }

    public function hasPlatformInfo(): bool
    {
        if (preg_match('/ov\((?P<platform>[\d_\.]+)\);/', $this->value)) {
            return false;
        }

        return (bool) preg_match('/ov\((?P<platform>[^\)]+)\);/', $this->value);
    }

    public function hasEngineInfo(): bool
    {
        return (bool) preg_match('/re\((?P<engine>[^\)]+)\)/', $this->value);
    }
}
