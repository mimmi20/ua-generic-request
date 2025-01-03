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
use UaParser\PlatformParserInterface;

use function preg_match;

final class XUcbrowserDeviceUa implements HeaderInterface
{
    use HeaderTrait;

    private readonly string $normalizedValue;

    /** @throws Exception */
    public function __construct(
        string $value,
        private readonly DeviceParserInterface $deviceParser,
        private readonly PlatformParserInterface $platformParser,
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
        return $this->value !== '?';
    }

    /** @throws void */
    #[Override]
    public function getDeviceCode(): string | null
    {
        if ($this->value === '?') {
            return null;
        }

        $code = $this->deviceParser->parse($this->normalizedValue);

        if ($code === '') {
            return null;
        }

        return $code;
    }

    /** @throws void */
    #[Override]
    public function hasPlatformCode(): bool
    {
        if ($this->value === '?') {
            return false;
        }

        return (bool) preg_match(
            '/bada|android|blackberry|brew|iphone|mre|windows|mtk|symbian/i',
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
        if ($this->value === '?') {
            return null;
        }

        $code = $this->platformParser->parse($this->normalizedValue);

        if ($code === '') {
            return null;
        }

        return $code;
    }
}
