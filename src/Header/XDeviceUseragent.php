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

/**
 * @deprecated use {@see DeviceCodeOnlyHeader}
 */
final class XDeviceUseragent implements HeaderInterface
{
    use HeaderTrait;

    private readonly string $normalizedValue;

    /** @throws Exception */
    public function __construct(
        string $value,
        private readonly DeviceParserInterface $deviceParser,
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
        return true;
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
}
