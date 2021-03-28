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

namespace UaRequest\Header;

use BrowserDetector\Loader\LoaderInterface;
use BrowserDetector\Loader\NotFoundException;

interface HeaderLoaderInterface extends LoaderInterface
{
    /**
     * @throws NotFoundException
     */
    public function load(string $key, ?string $value = null): HeaderInterface;
}
