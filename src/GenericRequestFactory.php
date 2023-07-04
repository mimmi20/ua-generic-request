<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2023, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequest;

use Laminas\Diactoros\HeaderSecurity;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\MessageInterface;
use UaRequest\Header\HeaderLoader;

use function mb_strpos;
use function mb_strtoupper;
use function preg_replace;

final class GenericRequestFactory implements GenericRequestFactoryInterface
{
    /**
     * Create a Generic Request from the given $userAgent
     *
     * @throws void
     */
    public function createRequestFromString(string $userAgent): GenericRequest
    {
        return $this->createRequestFromArray([Constants::HEADER_HTTP_USERAGENT => $userAgent]);
    }

    /**
     * Creates Generic Request from the given HTTP Request (normally $_SERVER).
     *
     * @param array<string, string> $headers HTTP Request
     *
     * @throws void
     */
    public function createRequestFromArray(array $headers): GenericRequest
    {
        $upperCaseHeaders = [];

        foreach ($headers as $header => $value) {
            $upperCaseHeader = mb_strtoupper($header);

            if (mb_strpos($upperCaseHeader, 'HTTP_') === false) {
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
     * Create a Generic Request from a given PSR-7 HTTP message
     *
     * @throws void
     */
    public function createRequestFromPsr7Message(MessageInterface $message): GenericRequest
    {
        return new GenericRequest($message, new HeaderLoader());
    }

    /** @throws void */
    private function filterHeader(string $header): string
    {
        return (string) preg_replace(
            ["#(?:(?:(?<!\r)\n)|(?:\r(?!\n))|(?:\r\n(?![ \t])))#", '/[^\x09\x0a\x0d\x20-\x7E\x80-\xFE]/'],
            '-',
            $header,
        );
    }
}
