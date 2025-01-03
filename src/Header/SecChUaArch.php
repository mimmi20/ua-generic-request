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

use function trim;

final class SecChUaArch implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    #[Override]
    public function hasDeviceArchitecture(): bool
    {
        $value = trim($this->value, '"\\\'');

        return $value !== '';
    }

    /** @throws void */
    #[Override]
    public function getDeviceArchitecture(): string | null
    {
        $value = trim($this->value, '"\\\'');

        if ($value === '') {
            return null;
        }

        return $value;
    }
}
