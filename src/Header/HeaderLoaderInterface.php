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

use BrowserDetector\Loader\LoaderInterface;

interface HeaderLoaderInterface extends LoaderInterface
{
    /**
     * @param string|null $key
     * @param string      $value
     *
     * @throws \BrowserDetector\Loader\NotFoundException
     *
     * @return \UaRequest\Header\HeaderInterface
     */
    public function load(string $key, ?string $value = null): HeaderInterface;
}
