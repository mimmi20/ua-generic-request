<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2019, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace UaRequest;

use Psr\Http\Message\MessageInterface;
use function Zend\Diactoros\marshalHeadersFromSapi;

final class GenericRequest implements GenericRequestInterface
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $filteredHeaders = [];

    /**
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->headers = [];

        foreach (array_keys($message->getHeaders()) as $header) {
            $this->headers[$header] = $message->getHeaderLine($header);
        }

        $this->filterHeaders();
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getFilteredHeaders(): array
    {
        return $this->filteredHeaders;
    }

    /**
     * @return string
     */
    public function getBrowserUserAgent(): string
    {
        $headers = [
            Constants::HEADER_SKYFIRE_VERSION    => true,
            Constants::HEADER_BLUECOAT_VIA       => true,
            Constants::HEADER_BOLT_PHONE_UA      => true,
            Constants::HEADER_UCBROWSER_UA       => true,
            Constants::HEADER_MOBILE_UA          => true,
            Constants::HEADER_REQUESTED_WITH     => true,
            Constants::HEADER_ORIGINAL_UA        => true,
            Constants::HEADER_DEVICE_STOCK_UA    => true,
            Constants::HEADER_OPERAMINI_PHONE_UA => true,
            Constants::HEADER_HTTP_USERAGENT     => true,
        ];

        foreach (array_keys(marshalHeadersFromSapi($headers)) as $header) {
            if (array_key_exists($header, $this->filteredHeaders)) {
                return $this->filteredHeaders[$header];
            }
        }

        return '';
    }

    /**
     * @return string
     */
    public function getDeviceUserAgent(): string
    {
        $headers = [
            Constants::HEADER_DEVICE_STOCK_UA     => true,
            Constants::HEADER_DEVICE_UA           => true,
            Constants::HEADER_UCBROWSER_UA        => true,
            Constants::HEADER_UCBROWSER_DEVICE_UA => true,
            Constants::HEADER_UCBROWSER_DEVICE    => true,
            Constants::HEADER_SKYFIRE_PHONE       => true,
            Constants::HEADER_OPERAMINI_PHONE_UA  => true,
            Constants::HEADER_ORIGINAL_UA         => true,
            Constants::HEADER_BAIDU_FLYFLOW       => true,
            Constants::HEADER_HTTP_USERAGENT      => true,
        ];

        foreach (array_keys(marshalHeadersFromSapi($headers)) as $header) {
            if (array_key_exists($header, $this->filteredHeaders)) {
                return $this->filteredHeaders[$header];
            }
        }

        return '';
    }

    /**
     * @return string
     */
    public function getPlatformUserAgent(): string
    {
        $headers = [
            Constants::HEADER_UA_OS           => true,
            Constants::HEADER_SKYFIRE_VERSION => true,
            Constants::HEADER_BLUECOAT_VIA    => true,
            Constants::HEADER_BOLT_PHONE_UA   => true,
            Constants::HEADER_UCBROWSER_UA    => true,
            Constants::HEADER_MOBILE_UA       => true,
            Constants::HEADER_REQUESTED_WITH  => true,
            Constants::HEADER_ORIGINAL_UA     => true,
            Constants::HEADER_HTTP_USERAGENT  => true,
        ];

        foreach (array_keys(marshalHeadersFromSapi($headers)) as $header) {
            if (array_key_exists($header, $this->filteredHeaders)) {
                return $this->filteredHeaders[$header];
            }
        }

        return '';
    }

    /**
     * @return void
     */
    private function filterHeaders(): void
    {
        $headers = [
            Constants::HEADER_DEVICE_STOCK_UA     => true,
            Constants::HEADER_DEVICE_UA           => true,
            Constants::HEADER_UCBROWSER_DEVICE_UA => true,
            Constants::HEADER_UCBROWSER_DEVICE    => true,
            Constants::HEADER_UCBROWSER_UA        => true,
            Constants::HEADER_SKYFIRE_PHONE       => true,
            Constants::HEADER_OPERAMINI_PHONE_UA  => true,
            Constants::HEADER_SKYFIRE_VERSION     => true,
            Constants::HEADER_BLUECOAT_VIA        => true,
            Constants::HEADER_BOLT_PHONE_UA       => true,
            Constants::HEADER_MOBILE_UA           => true,
            Constants::HEADER_REQUESTED_WITH      => true,
            Constants::HEADER_ORIGINAL_UA         => true,
            Constants::HEADER_UA_OS               => true,
            Constants::HEADER_BAIDU_FLYFLOW       => true,
            Constants::HEADER_HTTP_USERAGENT      => true,
            Constants::HEADER_WAP_PROFILE         => true,
        ];

        foreach (array_keys(marshalHeadersFromSapi($headers)) as $header) {
            if (!array_key_exists($header, $this->headers)) {
                continue;
            }

            $this->filteredHeaders[$header] = $this->headers[$header];
        }
    }
}
