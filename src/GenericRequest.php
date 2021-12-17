<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2021, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequest;

use Psr\Http\Message\MessageInterface;
use UaRequest\Header\HeaderInterface;
use UaRequest\Header\HeaderLoaderInterface;

use function array_filter;
use function array_key_exists;
use function array_keys;
use function is_string;

final class GenericRequest implements GenericRequestInterface
{
    private const HEADERS = [
        Constants::HEADER_SEC_CH_UA_MODEL,
        Constants::HEADER_SEC_CH_UA_PLATFORM,
        Constants::HEADER_SEC_CH_UA_PLATFORM_VERSION,
        Constants::HEADER_SEC_CH_UA_FULL_VERSION_LIST,
        Constants::HEADER_SEC_CH_UA,
        Constants::HEADER_SEC_CH_UA_FULL_VERSION,
        Constants::HEADER_SEC_CH_UA_BITNESS,
        Constants::HEADER_SEC_CH_UA_ARCH,
        Constants::HEADER_SEC_CH_UA_MOBILE,
        Constants::HEADER_DEVICE_UA,
        Constants::HEADER_UCBROWSER_UA,
        Constants::HEADER_UCBROWSER_DEVICE_UA,
        Constants::HEADER_UCBROWSER_DEVICE,
        Constants::HEADER_UCBROWSER_PHONE_UA,
        Constants::HEADER_UCBROWSER_PHONE,
        Constants::HEADER_DEVICE_STOCK_UA,
        Constants::HEADER_SKYFIRE_PHONE,
        Constants::HEADER_OPERAMINI_PHONE_UA,
        Constants::HEADER_OPERAMINI_PHONE,
        Constants::HEADER_SKYFIRE_VERSION,
        Constants::HEADER_BLUECOAT_VIA,
        Constants::HEADER_BOLT_PHONE_UA,
        Constants::HEADER_MOBILE_UA,
        Constants::HEADER_REQUESTED_WITH,
        Constants::HEADER_ORIGINAL_UA,
        Constants::HEADER_UA_OS,
        Constants::HEADER_BAIDU_FLYFLOW,
        Constants::HEADER_PUFFIN_UA,
        Constants::HEADER_USERAGENT,
        Constants::HEADER_WAP_PROFILE,
        Constants::HEADER_NB_CONTENT,
    ];
    /** @var array<string, string> */
    private array $headers = [];

    /** @var array<HeaderInterface> */
    private array $filteredHeaders = [];

    private HeaderLoaderInterface $loader;

    public function __construct(MessageInterface $message, HeaderLoaderInterface $loader)
    {
        $this->loader = $loader;

        foreach (array_keys($message->getHeaders()) as $header) {
            if (!is_string($header)) {
                continue;
            }

            $this->headers[$header] = $message->getHeaderLine($header);
        }

        $this->filterHeaders();
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array<string>
     */
    public function getFilteredHeaders(): array
    {
        $headers = [];

        foreach ($this->filteredHeaders as $name => $header) {
            $headers[$name] = $header->getValue();
        }

        return $headers;
    }

    public function getBrowserUserAgent(): string
    {
        foreach ($this->filteredHeaders as $header) {
            if ($header->hasBrowserInfo()) {
                return $header->getValue();
            }
        }

        return '';
    }

    public function getDeviceUserAgent(): string
    {
        foreach ($this->filteredHeaders as $header) {
            if ($header->hasDeviceInfo()) {
                return $header->getValue();
            }
        }

        return '';
    }

    public function getPlatformUserAgent(): string
    {
        foreach ($this->filteredHeaders as $header) {
            if ($header->hasPlatformInfo()) {
                return $header->getValue();
            }
        }

        return '';
    }

    public function getEngineUserAgent(): string
    {
        foreach ($this->filteredHeaders as $header) {
            if ($header->hasEngineInfo()) {
                return $header->getValue();
            }
        }

        return '';
    }

    private function filterHeaders(): void
    {
        $headers  = $this->headers;
        $filtered = array_filter(
            self::HEADERS,
            static fn ($value): bool => array_key_exists($value, $headers)
        );

        foreach ($filtered as $header) {
            try {
                $headerObj = $this->loader->load($header, $this->headers[$header]);
            } catch (NotFoundException $e) {
                continue;
            }

            $this->filteredHeaders[$header] = $headerObj;
        }
    }
}
