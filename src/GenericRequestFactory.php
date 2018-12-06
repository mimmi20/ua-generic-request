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
use Zend\Diactoros\HeaderSecurity;
use Zend\Diactoros\ServerRequestFactory;

final class GenericRequestFactory implements GenericRequestFactoryInterface
{
    /**
     * Creates Generic Request from the given HTTP Request (normally $_SERVER).
     *
     * @param array $headers HTTP Request
     *
     * @return \UaRequest\GenericRequest
     */
    public function createRequestFromArray(array $headers): GenericRequest
    {
        $upperCaseHeaders = [];

        foreach ($headers as $header => $value) {
            $upperCaseHeader = mb_strtoupper(str_replace('-', '_', $header));

            if (0 !== mb_strpos($upperCaseHeader, 'HTTP_')) {
                $upperCaseHeader = 'HTTP_' . $upperCaseHeader;
            }

            if (!HeaderSecurity::isValid($value)) {
                $value = $this->filterHeader($value);
            }

            $upperCaseHeaders[$upperCaseHeader] = $value;
        }

        $message = ServerRequestFactory::fromGlobals($upperCaseHeaders);

        return $this->createRequestFromPsr7Message($message);
    }

    /**
     * Create a Generic Request from the given $userAgent
     *
     * @param string $userAgent
     *
     * @return \UaRequest\GenericRequest
     */
    public function createRequestFromString(string $userAgent): GenericRequest
    {
        if (!HeaderSecurity::isValid($userAgent)) {
            $userAgent = $this->filterHeader($userAgent);
        }

        $message = ServerRequestFactory::fromGlobals([Constants::HEADER_HTTP_USERAGENT => $userAgent]);

        return $this->createRequestFromPsr7Message($message);
    }

    /**
     * Create a Generic Request from a given PSR-7 HTTP message
     *
     * @param \Psr\Http\Message\MessageInterface $message
     *
     * @return \UaRequest\GenericRequest
     */
    public function createRequestFromPsr7Message(MessageInterface $message): GenericRequest
    {
        return new GenericRequest($message);
    }

    /**
     * @param string $header
     *
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    private function filterHeader(string $header): string
    {
        $filtered = preg_replace(
            ["#(?:(?:(?<!\r)\n)|(?:\r(?!\n))|(?:\r\n(?![ \t])))#", '/[^\x09\x0a\x0d\x20-\x7E\x80-\xFE]/'],
            '-',
            $header
        );

        if (null === $filtered) {
            throw new \UnexpectedValueException(sprintf('an error occurecd while filtering header "%s"', $header));
        }

        return $filtered;
    }
}
