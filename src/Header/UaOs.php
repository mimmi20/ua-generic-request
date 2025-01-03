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

use function preg_match;

final class UaOs implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    #[Override]
    public function hasPlatformCode(): bool
    {
        return (bool) preg_match('/Windows CE \(Pocket PC\) - Version \d+\.\d+/', $this->value);
    }

    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getPlatformCode(string | null $derivate = null): string | null
    {
        $matches = [];

        if (
            preg_match(
                '/(?P<name>Windows CE) \(Pocket PC\) - Version \d+\.\d+/',
                $this->value,
                $matches,
            )
        ) {
            return 'windows ce';
        }

        return null;
    }

    /** @throws void */
    #[Override]
    public function hasPlatformVersion(): bool
    {
        return (bool) preg_match('/Windows CE \(Pocket PC\) - Version \d+\.\d+/', $this->value);
    }

    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getPlatformVersion(string | null $code = null): string | null
    {
        $matches = [];

        if (
            preg_match(
                '/Windows CE \(Pocket PC\) - Version (?P<version>\d+\.\d+)/',
                $this->value,
                $matches,
            )
        ) {
            return $matches['version'];
        }

        return null;
    }
}
