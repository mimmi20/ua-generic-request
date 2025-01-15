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

use function serialize;
use function sha1;

final class GenericRequest implements GenericRequestInterface
{
    /** @var array<non-empty-string, HeaderInterface> */
    private array $headers = [];

    /** @throws void */
    public function __construct(MessageInterface $message, private readonly HeaderLoaderInterface $headerLoader)
    {
        $filtered = Headers::filter($message);

        foreach ($filtered as $header => $headerLine) {
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
