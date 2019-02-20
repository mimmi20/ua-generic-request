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
namespace UaRequest\Header;

final class XOriginalUseragent implements HeaderInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * Useragent constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Retrieve header value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function hasDeviceInfo(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function hasBrowserInfo(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function hasPlatformInfo(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function hasEngineInfo(): bool
    {
        return true;
    }
}
