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

namespace UaRequest;

use Override;
use Psr\Http\Message\MessageInterface;
use UaRequest\Header\HeaderInterface;
use UaRequest\Header\HeaderLoaderInterface;

use function array_filter;
use function array_key_exists;
use function array_keys;
use function is_string;
use function mb_strtolower;
use function mb_substr;
use function serialize;
use function sha1;
use function str_starts_with;

final class GenericRequest implements GenericRequestInterface
{
    private const array HEADERS = [
        Constants::HEADER_SEC_CH_UA_MODEL,
        Constants::HEADER_SEC_CH_UA_PLATFORM,
        Constants::HEADER_SEC_CH_UA_PLATFORM_VERSION,
        Constants::HEADER_SEC_CH_UA_FULL_VERSION_LIST,
        Constants::HEADER_REQUESTED_WITH,
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
        Constants::HEADER_OPERAMINI_PHONE_UA,
        Constants::HEADER_DEVICE_STOCK_UA,
        Constants::HEADER_OPERAMINI_PHONE,
        Constants::HEADER_ORIGINAL_UA,
        Constants::HEADER_UA_OS,
        Constants::HEADER_BAIDU_FLYFLOW,
        Constants::HEADER_PUFFIN_UA,
        Constants::HEADER_USERAGENT,
    ];

    /** @var array<non-empty-string, HeaderInterface> */
    private array $headers = [];

    /** @throws void */
    public function __construct(MessageInterface $message, private readonly HeaderLoaderInterface $headerLoader)
    {
        $filteredHeaders = array_filter(
            array: $message->getHeaders(),
            callback: function (int|string $header) use ($message): bool {
                if (!is_string($header) || $header === '') {
                    return false;
                }

                $headerLine = $message->getHeaderLine($header);

                if ($headerLine === '') {
                    return false;
                }

                $header = mb_strtolower($header);

                if (str_starts_with($header, 'http-') || str_starts_with($header, 'http_')) {
                    $header = mb_substr($header, 5);
                }

                return $header !== '';
            },
            mode: ARRAY_FILTER_USE_KEY,
        );

        $filtered = array_filter(
            array: self::HEADERS,
            callback: static fn (string $value): bool => array_key_exists(mb_strtolower($value), $filteredHeaders),
        );

        foreach ($filtered as $header) {
            $headerLine = $message->getHeaderLine($header);

            try {
                $headerObj = $this->headerLoader->load($header, $headerLine);
            } catch (NotFoundException) {
                continue;
            }

            $this->headers[$header] = $headerObj;
        }
    }

    /**
     * @return array<non-empty-string, HeaderInterface>
     *
     * @throws void
     */
    #[Override]
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /** @throws void */
    #[Override]
    public function getHash(): string
    {
        $data = [];

        foreach ($this->headers as $name => $header) {
            $data[$name] = $header->getValue();
        }

        return sha1(serialize($data));
    }
}
