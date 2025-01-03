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
use UaParser\DeviceParserInterface;

use function mb_strtolower;
use function preg_match;
use function str_replace;

final class DeviceStockUa implements HeaderInterface
{
    use HeaderTrait;

    /** @throws void */
    public function __construct(string $value, private readonly DeviceParserInterface $deviceParser)
    {
        $this->value = $value;
    }

    /** @throws void */
    #[Override]
    public function hasDeviceCode(): bool
    {
        return (bool) preg_match(
            '/samsung|nokia|blackberry|smartfren|sprint|iphone|lava|gionee|philips|htc|mi 2sc/i',
            $this->value,
        );
    }

    /** @throws void */
    #[Override]
    public function getDeviceCode(): string | null
    {
        if (
            !preg_match(
                '/samsung|nokia|blackberry|smartfren|sprint|iphone|lava|gionee|philips|htc|mi 2sc/i',
                $this->value,
            )
        ) {
            return null;
        }

        $code = $this->deviceParser->parse($this->value);

        if ($code === '') {
            return null;
        }

        return $code;
    }

    /** @throws void */
    #[Override]
    public function hasClientCode(): bool
    {
        return (bool) preg_match('/opera mini|iemobile/i', $this->value);
    }

    /** @throws void */
    #[Override]
    public function getClientCode(): string | null
    {
        $matches = [];

        if (preg_match('/(?P<client>opera mini|iemobile)/i', $this->value, $matches)) {
            return mb_strtolower($matches['client']);
        }

        return null;
    }

    /** @throws void */
    #[Override]
    public function hasClientVersion(): bool
    {
        return (bool) preg_match('/(?:opera mini|iemobile)\/[\d\.]+/i', $this->value);
    }

    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getClientVersion(string | null $code = null): string | null
    {
        $matches = [];

        if (preg_match('/(?:opera mini|iemobile)\/(?P<version>[\d\.]+)/i', $this->value, $matches)) {
            return $matches['version'];
        }

        return null;
    }

    /** @throws void */
    #[Override]
    public function hasPlatformCode(): bool
    {
        return (bool) preg_match(
            '/bada|android|blackberry|brew(?: mp)?|iphone os|mre|windows phone(?: os)?|mtk/i',
            $this->value,
        );
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
                '/(?P<platform>bada|android|blackberry|brew(?: mp)?|iphone os|mre|windows phone(?: os)?|mtk)/i',
                $this->value,
                $matches,
            )
        ) {
            $code = mb_strtolower($matches['platform']);

            return match ($code) {
                'blackberry' => 'rim os',
                'iphone os' => 'ios',
                'mtk' => 'nucleus os',
                'windows phone os' => 'windows phone',
                'brew mp' => 'brew',
                default => $code,
            };
        }

        return null;
    }

    /** @throws void */
    #[Override]
    public function hasPlatformVersion(): bool
    {
        return (bool) preg_match(
            '/(bada|android|blackberry\d{4}|brew(?: mp)?|iphone os|windows phone(?: os)?)[\/ ][\d._]+/i',
            $this->value,
        );
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
                '/(?:bada|android|blackberry\d{4}|brew(?: mp)?|iphone os|windows phone(?: os)?)[\/ ](?P<version>[\d._]+)/i',
                $this->value,
                $matches,
            )
        ) {
            return str_replace('_', '.', $matches['version']);
        }

        return null;
    }

    /** @throws void */
    #[Override]
    public function hasEngineCode(): bool
    {
        return (bool) preg_match('/trident|presto|webkit|gecko/i', $this->value);
    }

    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getEngineCode(string | null $code = null): string | null
    {
        $matches = [];

        if (preg_match('/(?P<engine>trident|presto|webkit|gecko)/i', $this->value, $matches)) {
            return mb_strtolower($matches['engine']);
        }

        return null;
    }

    /** @throws void */
    #[Override]
    public function hasEngineVersion(): bool
    {
        return (bool) preg_match('/(?:trident|presto|webkit|gecko)[\/ ]([\d._]+)/i', $this->value);
    }

    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getEngineVersion(string | null $code = null): string | null
    {
        $matches = [];

        if (
            preg_match(
                '/(?:trident|presto|webkit|gecko)[\/ ](?P<version>[\d._]+)/i',
                $this->value,
                $matches,
            )
        ) {
            return str_replace('_', '.', $matches['version']);
        }

        return null;
    }
}
