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
use UaResult\Device\FormFactor;
use ValueError;

use function preg_match_all;

final class SecChUaFormFactors implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    #[Override]
    public function hasDeviceFormFactor(): bool
    {
        $matches = [];

        if (preg_match_all('~["\']([a-z]+)["\']~i', $this->value, $matches)) {
            foreach ($matches[1] as $factor) {
                try {
                    FormFactor::from($factor);

                    return true;
                } catch (ValueError | TypeError) {
                    // do nothing
                }
            }
        }

        return false;
    }

    /**
     * @return list<FormFactor>
     *
     * @throws void
     */
    #[Override]
    public function getDeviceFormFactor(): array
    {
        $matches = [];
        $factors = [];

        if (preg_match_all('~["\']([a-z]+)["\']~i', $this->value, $matches)) {
            foreach ($matches[1] as $factor) {
                try {
                    $factors[] = FormFactor::from($factor);
                } catch (ValueError | TypeError) {
                    $factors[] = FormFactor::unknown;
                }
            }
        }

        return $factors;
    }
}
