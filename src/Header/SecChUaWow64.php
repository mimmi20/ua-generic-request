<?php

/**
 * This file is part of the mimmi20/ua-generic-request package.
 *
 * Copyright (c) 2015-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequest\Header;

use Override;

final class SecChUaWow64 implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    #[Override]
    public function hasDeviceIsWow64(): bool
    {
        return true;
    }

    /** @throws void */
    #[Override]
    public function getDeviceIsWow64(): bool
    {
        return $this->value === '1' || $this->value === '?1' || $this->value === '"?1"';
    }
}
