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

use Closure;
use Override;
use UaLoader\BrowserLoaderInterface;
use UaLoader\EngineLoaderInterface;
use UaLoader\PlatformLoaderInterface;
use UaNormalizer\NormalizerFactory;
use UaParser\BrowserParserInterface;
use UaParser\DeviceParserInterface;
use UaParser\EngineParserInterface;
use UaParser\PlatformParserInterface;
use UaRequest\Constants;
use UaRequest\NotFoundException;

use function array_key_exists;
use function sprintf;

final class HeaderLoader implements HeaderLoaderInterface
{
    /** @var array<string, Closure(string): HeaderInterface> */
    private array $factories;

    /** @throws void */
    public function __construct(
        DeviceParserInterface $deviceParser,
        PlatformParserInterface $platformParser,
        BrowserParserInterface $browserParser,
        EngineParserInterface $engineParser,
        NormalizerFactory $normalizerFactory,
        BrowserLoaderInterface $browserLoader,
        PlatformLoaderInterface $platformLoader,
        EngineLoaderInterface $engineLoader,
    ) {
        $this->factories = [
            Constants::HEADER_BAIDU_FLYFLOW => static fn (string $header): HeaderInterface => new BaiduFlyflow(
                value: $header,
                deviceParser: $deviceParser,
            ),
            Constants::HEADER_DEVICE_STOCK_UA => static fn (string $header): HeaderInterface => new DeviceStockUa(
                value: $header,
                deviceParser: $deviceParser,
            ),
            Constants::HEADER_SEC_CH_UA => static fn (string $header): HeaderInterface => new SecChUa(
                value: $header,
            ),
            Constants::HEADER_SEC_CH_UA_ARCH => static fn (string $header): HeaderInterface => new SecChUaArch(
                value: $header,
            ),
            Constants::HEADER_SEC_CH_UA_BITNESS => static fn (string $header): HeaderInterface => new SecChUaBitness(
                value: $header,
            ),
            Constants::HEADER_SEC_CH_UA_FULL_VERSION => static fn (string $header): HeaderInterface => new SecChUaFullVersion(
                value: $header,
            ),
            Constants::HEADER_SEC_CH_UA_FULL_VERSION_LIST => static fn (string $header): HeaderInterface => new SecChUa(
                value: $header,
            ),
            Constants::HEADER_SEC_CH_UA_MOBILE => static fn (string $header): HeaderInterface => new SecChUaMobile(
                value: $header,
            ),
            Constants::HEADER_SEC_CH_UA_MODEL => static fn (string $header): HeaderInterface => new SecChUaModel(
                value: $header,
            ),
            Constants::HEADER_SEC_CH_UA_PLATFORM => static fn (string $header): HeaderInterface => new SecChUaPlatform(
                value: $header,
            ),
            Constants::HEADER_SEC_CH_UA_PLATFORM_VERSION => static fn (string $header): HeaderInterface => new SecChUaPlatformVersion(
                value: $header,
            ),
            Constants::HEADER_UA_OS => static fn (string $header): HeaderInterface => new UaOs($header),
            Constants::HEADER_USERAGENT => static fn (string $header): HeaderInterface => new Useragent(
                value: $header,
                deviceParser: $deviceParser,
                platformParser: $platformParser,
                browserParser: $browserParser,
                engineParser: $engineParser,
                normalizerFactory: $normalizerFactory,
                browserLoader: $browserLoader,
                platformLoader: $platformLoader,
                engineLoader: $engineLoader,
            ),
            Constants::HEADER_ORIGINAL_UA => static fn (string $header): HeaderInterface => new Useragent(
                value: $header,
                deviceParser: $deviceParser,
                platformParser: $platformParser,
                browserParser: $browserParser,
                engineParser: $engineParser,
                normalizerFactory: $normalizerFactory,
                browserLoader: $browserLoader,
                platformLoader: $platformLoader,
                engineLoader: $engineLoader,
            ),
            Constants::HEADER_DEVICE_UA => static fn (string $header): HeaderInterface => new XDeviceUseragent(
                value: $header,
                deviceParser: $deviceParser,
                normalizerFactory: $normalizerFactory,
            ),
            Constants::HEADER_OPERAMINI_PHONE => static fn (string $header): HeaderInterface => new XOperaminiPhone(
                value: $header,
            ),
            Constants::HEADER_OPERAMINI_PHONE_UA => static fn (string $header): HeaderInterface => new XOperaminiPhoneUa(
                value: $header,
                deviceParser: $deviceParser,
                engineParser: $engineParser,
                normalizerFactory: $normalizerFactory,
            ),
            Constants::HEADER_PUFFIN_UA => static fn (string $header): HeaderInterface => new XPuffinUa(
                value: $header,
            ),
            Constants::HEADER_REQUESTED_WITH => static fn (string $header): HeaderInterface => new XRequestedWith(
                value: $header,
            ),
            Constants::HEADER_UCBROWSER_DEVICE => static fn (string $header): HeaderInterface => new XUcbrowserDevice(
                value: $header,
                deviceParser: $deviceParser,
                normalizerFactory: $normalizerFactory,
            ),
            Constants::HEADER_UCBROWSER_DEVICE_UA => static fn (string $header): HeaderInterface => new XUcbrowserDeviceUa(
                value: $header,
                deviceParser: $deviceParser,
                platformParser: $platformParser,
                normalizerFactory: $normalizerFactory,
            ),
            Constants::HEADER_UCBROWSER_PHONE => static fn (string $header): HeaderInterface => new XUcbrowserPhone(
                value: $header,
            ),
            Constants::HEADER_UCBROWSER_PHONE_UA => static fn (string $header): HeaderInterface => new XUcbrowserPhoneUa(
                value: $header,
            ),
            Constants::HEADER_UCBROWSER_UA => static fn (string $header): HeaderInterface => new XUcbrowserUa(
                value: $header,
                deviceParser: $deviceParser,
            ),
        ];
    }

    /**
     * @param Constants::HEADER_* $key
     *
     * @throws void
     */
    #[Override]
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->factories);
    }

    /**
     * @param Constants::HEADER_* $key
     *
     * @throws NotFoundException
     */
    #[Override]
    public function load(string $key, string $value): HeaderInterface
    {
        if (!$this->has($key)) {
            throw new NotFoundException(sprintf('the header with name "%s" was not found', $key));
        }

        $factory = $this->factories[$key];

        return $factory($value);
    }
}
