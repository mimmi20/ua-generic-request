<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2018, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace UaRequest;

use Psr\Http\Message\MessageInterface;
use Zend\Diactoros\ServerRequestFactory;

class GenericRequest
{
    /**
     * @var array
     */
    private $userAgentSearchOrder = [
        Constants::HEADER_DEVICE_STOCK_UA     => 'device',
        Constants::HEADER_DEVICE_UA           => 'device',
        Constants::HEADER_UCBROWSER_DEVICE_UA => 'device',
        Constants::HEADER_SKYFIRE_PHONE       => 'device',
        Constants::HEADER_OPERAMINI_PHONE_UA  => 'device',
        Constants::HEADER_SKYFIRE_VERSION     => 'browser',
        Constants::HEADER_BLUECOAT_VIA        => 'browser',
        Constants::HEADER_BOLT_PHONE_UA       => 'browser',
        Constants::HEADER_UCBROWSER_UA        => 'browser',
        Constants::HEADER_MOBILE_UA           => 'browser',
        Constants::HEADER_REQUESTED_WITH      => 'browser',
        Constants::HEADER_ORIGINAL_UA         => 'generic',
        Constants::HEADER_HTTP_USERAGENT      => 'generic',
    ];

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
        foreach (ServerRequestFactory::marshalHeaders($this->userAgentSearchOrder) as $header => $type) {
            if (!in_array($type, ['browser', 'generic'])) {
                continue;
            }

            if (isset($this->filteredHeaders[$header])) {
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
        foreach (ServerRequestFactory::marshalHeaders($this->userAgentSearchOrder) as $header => $type) {
            if (!in_array($type, ['device', 'generic'])) {
                continue;
            }

            if (isset($this->filteredHeaders[$header])) {
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
        foreach (array_keys(ServerRequestFactory::marshalHeaders($this->userAgentSearchOrder)) as $header) {
            if (!array_key_exists($header, $this->headers)) {
                continue;
            }

            $this->filteredHeaders[$header] = $this->headers[$header];
        }
    }
}
