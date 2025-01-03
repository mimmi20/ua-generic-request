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
use UaNormalizer\Normalizer\Exception\Exception;
use UaNormalizer\NormalizerFactory;
use UaParser\DeviceParserInterface;
use UaParser\EngineParserInterface;

use function mb_strtolower;
use function preg_match;

final class XOperaminiPhoneUa implements HeaderInterface
{
    use HeaderTrait;

    private readonly string $normalizedValue;

    /** @throws Exception */
    public function __construct(
        string $value,
        private readonly DeviceParserInterface $deviceParser,
        private readonly EngineParserInterface $engineParser,
        private readonly NormalizerFactory $normalizerFactory,
    ) {
        $this->value = $value;

        $normalizer = $this->normalizerFactory->build();

        $this->normalizedValue = $normalizer->normalize($value);
    }

    /** @throws void */
    #[Override]
    public function hasDeviceCode(): bool
    {
        return (bool) preg_match(
            '/samsung|nokia|blackberry|smartfren|sprint|iphone|lava|gionee|philips|htc|pantech|lg|casio|zte|mi 2sc|sm-g900f|gt-i9000|gt-s5830i|sne-lx1/i',
            $this->value,
        );
    }

    /** @throws void */
    #[Override]
    public function getDeviceCode(): string | null
    {
        $code = $this->deviceParser->parse($this->normalizedValue);

        if ($code === '') {
            return null;
        }

        return $code;
    }

    /** @throws void */
    #[Override]
    public function hasClientCode(): bool
    {
        return (bool) preg_match('/opera mini/i', $this->value);
    }

    /** @throws void */
    #[Override]
    public function getClientCode(): string
    {
        return 'opera mini';
    }

    /** @throws void */
    #[Override]
    public function hasClientVersion(): bool
    {
        return (bool) preg_match('/opera mini\/[\d\.]+/i', $this->value);
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

        if (preg_match('/opera mini\/(?P<version>[\d\.]+)/i', $this->value, $matches)) {
            return $matches['version'];
        }

        return null;
    }

    /** @throws void */
    #[Override]
    public function hasPlatformCode(): bool
    {
        return (bool) preg_match(
            '/bada|android|blackberry|brew|iphone|mre|windows|mtk/i',
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
                '/(?P<platform>bada|android|blackberry|brew|iphone|mre|windows( ce)?|mtk)/i',
                $this->value,
                $matches,
            )
        ) {
            $code = mb_strtolower($matches['platform']);

            return match ($code) {
                'blackberry' => 'rim os',
                'windows' => 'windows phone',
                'iphone' => 'ios',
                'mtk' => 'nucleus os',
                default => $code,
            };
        }

        return null;
    }

    /** @throws void */
    #[Override]
    public function hasEngineCode(): bool
    {
        return (bool) preg_match('/trident|presto|webkit|gecko/i', $this->value);
    }

    /** @throws void */
    #[Override]
    public function getEngineCode(string | null $code = null): string | null
    {
        $code = $this->engineParser->parse($this->normalizedValue);

        if ($code === '') {
            return null;
        }

        return $code;
    }
}
