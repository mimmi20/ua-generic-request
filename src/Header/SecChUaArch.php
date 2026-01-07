<?php

/**
 * This file is part of the mimmi20/ua-generic-request package.
 *
 * Copyright (c) 2015-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequest\Header;

use Override;
use TypeError;
use UaResult\Device\Architecture;
use ValueError;

use function mb_trim;

final class SecChUaArch implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    #[Override]
    public function hasDeviceArchitecture(): bool
    {
        $value = mb_trim($this->value, '"\\\'');

        if ($value === '') {
            return false;
        }

        try {
            Architecture::from($value);
        } catch (ValueError | TypeError) {
            return false;
        }

        return true;
    }

    /** @throws void */
    #[Override]
    public function getDeviceArchitecture(): Architecture
    {
        $value = mb_trim($this->value, '"\\\'');

        try {
            return Architecture::from($value);
        } catch (ValueError | TypeError) {
            return Architecture::unknown;
        }
    }
}
