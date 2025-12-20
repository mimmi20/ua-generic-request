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

use Psr\Http\Message\MessageInterface;

use function array_keys;
use function array_multisort;
use function is_string;
use function mb_strtolower;
use function mb_substr;
use function str_starts_with;

use const SORT_ASC;
use const SORT_NUMERIC;

enum Headers: string
{
    case HEADER_BAIDU_FLYFLOW = Constants::HEADER_BAIDU_FLYFLOW;

    case HEADER_DEVICE_STOCK_UA = Constants::HEADER_DEVICE_STOCK_UA;

    case HEADER_SEC_CH_UA = Constants::HEADER_SEC_CH_UA;

    case HEADER_SEC_CH_UA_ARCH = Constants::HEADER_SEC_CH_UA_ARCH;

    case HEADER_SEC_CH_UA_BITNESS = Constants::HEADER_SEC_CH_UA_BITNESS;

    case HEADER_SEC_CH_FORM_FACTORS = Constants::HEADER_SEC_CH_FORM_FACTORS;

    case HEADER_SEC_CH_UA_FULL_VERSION = Constants::HEADER_SEC_CH_UA_FULL_VERSION;

    case HEADER_SEC_CH_UA_FULL_VERSION_LIST = Constants::HEADER_SEC_CH_UA_FULL_VERSION_LIST;

    case HEADER_SEC_CH_UA_MOBILE = Constants::HEADER_SEC_CH_UA_MOBILE;

    case HEADER_SEC_CH_UA_MODEL = Constants::HEADER_SEC_CH_UA_MODEL;

    case HEADER_SEC_CH_UA_PLATFORM = Constants::HEADER_SEC_CH_UA_PLATFORM;

    case HEADER_SEC_CH_UA_PLATFORM_VERSION = Constants::HEADER_SEC_CH_UA_PLATFORM_VERSION;

    case HEADER_SEC_CH_WOW64 = Constants::HEADER_SEC_CH_WOW64;

    case HEADER_UA_OS = Constants::HEADER_UA_OS;

    case HEADER_CRAWLED_BY = Constants::HEADER_CRAWLED_BY;

    case HEADER_USERAGENT = Constants::HEADER_USERAGENT;

    case HEADER_ORIGINAL_UA = Constants::HEADER_ORIGINAL_UA;

    case HEADER_DEVICE_UA = Constants::HEADER_DEVICE_UA;

    case HEADER_OPERAMINI_PHONE = Constants::HEADER_OPERAMINI_PHONE;

    case HEADER_OPERAMINI_PHONE_UA = Constants::HEADER_OPERAMINI_PHONE_UA;

    case HEADER_PUFFIN_UA = Constants::HEADER_PUFFIN_UA;

    case HEADER_REQUESTED_WITH = Constants::HEADER_REQUESTED_WITH;

    case HEADER_UCBROWSER_DEVICE = Constants::HEADER_UCBROWSER_DEVICE;

    case HEADER_UCBROWSER_DEVICE_UA = Constants::HEADER_UCBROWSER_DEVICE_UA;

    case HEADER_UCBROWSER_PHONE = Constants::HEADER_UCBROWSER_PHONE;

    case HEADER_UCBROWSER_PHONE_UA = Constants::HEADER_UCBROWSER_PHONE_UA;

    case HEADER_UCBROWSER_UA = Constants::HEADER_UCBROWSER_UA;

    /**
     * @return array<non-empty-string, non-empty-string>
     *
     * @throws void
     */
    public static function filter(MessageInterface $message): array
    {
        $filteredHeaders = [];

        foreach (array_keys($message->getHeaders()) as $header) {
            if (!is_string($header) || $header === '') {
                continue;
            }

            $headerLine = $message->getHeaderLine($header);

            if ($headerLine === '') {
                continue;
            }

            $header = mb_strtolower($header);

            if (str_starts_with($header, 'http-') || str_starts_with($header, 'http_')) {
                $header = mb_substr($header, 5);
            }

            if ($header === '' || self::tryFrom($header) === null) {
                continue;
            }

            $filteredHeaders[$header] = $headerLine;
        }

        $headerSort = [];

        foreach (array_keys($filteredHeaders) as $header) {
            $enum = self::tryFrom($header);

            $headerSort[$header] = match ($enum) {
                self::HEADER_SEC_CH_UA_MODEL => 1,
                self::HEADER_SEC_CH_UA_PLATFORM => 2,
                self::HEADER_SEC_CH_UA_PLATFORM_VERSION => 3,
                self::HEADER_REQUESTED_WITH => 4,
                self::HEADER_SEC_CH_UA_FULL_VERSION_LIST => 5,
                self::HEADER_SEC_CH_UA => 6,
                self::HEADER_SEC_CH_UA_FULL_VERSION => 7,
                self::HEADER_SEC_CH_UA_BITNESS => 8,
                self::HEADER_SEC_CH_UA_ARCH => 9,
                self::HEADER_SEC_CH_FORM_FACTORS => 10,
                self::HEADER_SEC_CH_UA_MOBILE => 11,
                self::HEADER_SEC_CH_WOW64 => 12,
                self::HEADER_DEVICE_UA => 13,
                self::HEADER_UCBROWSER_UA => 14,
                self::HEADER_UCBROWSER_DEVICE_UA => 15,
                self::HEADER_UCBROWSER_DEVICE => 16,
                self::HEADER_UCBROWSER_PHONE_UA => 17,
                self::HEADER_UCBROWSER_PHONE => 18,
                self::HEADER_OPERAMINI_PHONE_UA => 19,
                self::HEADER_DEVICE_STOCK_UA => 20,
                self::HEADER_OPERAMINI_PHONE => 21,
                self::HEADER_ORIGINAL_UA => 22,
                self::HEADER_UA_OS => 23,
                self::HEADER_BAIDU_FLYFLOW => 24,
                self::HEADER_PUFFIN_UA => 25,
                self::HEADER_CRAWLED_BY => 26,
                default => 27,
            };
        }

        array_multisort($headerSort, SORT_ASC, SORT_NUMERIC, $filteredHeaders);

        return $filteredHeaders;
    }
}
