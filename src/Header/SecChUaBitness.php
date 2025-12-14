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
use TypeError;
use UaResult\Bits\Bits;
use ValueError;

use function is_numeric;
use function mb_trim;

final class SecChUaBitness implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    #[Override]
    public function hasDeviceBitness(): bool
    {
        $value = mb_trim($this->value, '"\\\'');

        if ($value === '' || !is_numeric($value)) {
            return false;
        }

        try {
            Bits::from((int) $value);
        } catch (ValueError | TypeError) {
            return false;
        }

        return true;
    }

    /** @throws void */
    #[Override]
    public function getDeviceBitness(): Bits
    {
        $value = mb_trim($this->value, '"\\\'');

        try {
            return Bits::from((int) $value);
        } catch (ValueError | TypeError) {
            return Bits::unknown;
        }
    }
}
