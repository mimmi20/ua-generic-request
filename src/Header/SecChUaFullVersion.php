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

use BrowserDetector\Version\Exception\NotNumericException;
use BrowserDetector\Version\VersionBuilder;
use BrowserDetector\Version\VersionInterface;
use Override;

use function mb_trim;

final class SecChUaFullVersion implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    #[Override]
    public function hasClientVersion(): bool
    {
        $value = mb_trim($this->value, '"\\\'');

        return $value !== '';
    }

    /**
     * @throws NotNumericException
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getClientVersion(string | null $code = null): VersionInterface
    {
        $value = mb_trim($this->value, '"\\\'');

        return (new VersionBuilder())->set($value);
    }
}
