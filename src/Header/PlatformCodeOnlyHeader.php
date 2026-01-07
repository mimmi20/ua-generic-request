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
use UaData\OsInterface;
use UaParser\PlatformCodeInterface;
use UaRequest\Exception\NotFoundException;

final class PlatformCodeOnlyHeader implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    public function __construct(string $value, private readonly PlatformCodeInterface $platformCode)
    {
        $this->value = $value;
    }

    /** @throws void */
    #[Override]
    public function hasPlatformCode(): bool
    {
        return $this->platformCode->hasPlatformCode($this->value);
    }

    /** @throws NotFoundException */
    #[Override]
    public function getPlatformCode(string | null $derivate = null): OsInterface
    {
        return $this->platformCode->getPlatformCode($this->value, $derivate);
    }
}
