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
namespace UaRequestTest;

use ExceptionalJSON\DecodeErrorException;
use JsonClass\Json;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use UaRequest\Constants;
use UaRequest\GenericRequest;
use UaRequest\GenericRequestFactory;
use Zend\Diactoros\ServerRequestFactory;

final class GenericRequestFactoryTest extends TestCase
{
    /**
     * @var \UaRequest\GenericRequestFactory
     */
    private $object;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->object = new GenericRequestFactory();
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testCreateRequestFromArray(): void
    {
        $userAgent = 'testUA';
        $headers   = [
            Constants::HEADER_USERAGENT => $userAgent,
        ];

        $result = $this->object->createRequestFromArray($headers);

        static::assertInstanceOf(GenericRequest::class, $result);
        static::assertSame($headers, $result->getHeaders());
        static::assertSame($userAgent, $result->getBrowserUserAgent());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testCreateRequestFromEmptyHeaders(): void
    {
        $headers = [];

        $result = $this->object->createRequestFromArray($headers);

        static::assertInstanceOf(GenericRequest::class, $result);
        static::assertSame($headers, $result->getHeaders());
        static::assertSame('', $result->getBrowserUserAgent());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testCreateRequestFromString(): void
    {
        $userAgent = 'testUA';
        $headers   = [
            Constants::HEADER_USERAGENT => $userAgent,
        ];

        $result = $this->object->createRequestFromString($userAgent);

        static::assertInstanceOf(GenericRequest::class, $result);
        static::assertSame($headers, $result->getHeaders());
        static::assertSame($userAgent, $result->getBrowserUserAgent());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testCreateRequestFromPsr7Message(): void
    {
        $userAgent = 'testUA';
        $deviceUa  = 'testDeviceUa';
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            'HTTP_X_UCBROWSER_DEVICE_UA' => $deviceUa,
        ];
        $expectedHeaders = [
            Constants::HEADER_USERAGENT => $userAgent,
            Constants::HEADER_UCBROWSER_DEVICE_UA => $deviceUa,
        ];

        $result = $this->object->createRequestFromPsr7Message(ServerRequestFactory::fromGlobals($headers));

        static::assertInstanceOf(GenericRequest::class, $result);
        static::assertSame($expectedHeaders, $result->getHeaders());
        static::assertSame($userAgent, $result->getBrowserUserAgent());
        static::assertSame($deviceUa, $result->getDeviceUserAgent());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testCreateRequestFromInvalidString(): void
    {
        $userAgent = "Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY\nPM)";
        $resultUa  = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY-PM)';
        $headers   = [
            Constants::HEADER_USERAGENT => $resultUa,
        ];

        $result = $this->object->createRequestFromString($userAgent);

        static::assertInstanceOf(GenericRequest::class, $result);
        static::assertSame($headers, $result->getHeaders());
        static::assertSame($resultUa, $result->getBrowserUserAgent());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testCreateRequestFromInvalidArray(): void
    {
        $userAgent = "Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY\nPM)";
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $resultUa        = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY-PM)';
        $expectedHeaders = [
            Constants::HEADER_USERAGENT => $resultUa,
        ];

        $result = $this->object->createRequestFromArray($headers);

        static::assertInstanceOf(GenericRequest::class, $result);
        static::assertSame($expectedHeaders, $result->getHeaders());
        static::assertSame($resultUa, $result->getBrowserUserAgent());
    }

    /**
     * @dataProvider providerUa
     *
     * @param array  $headers
     * @param string $expectedDeviceUa
     * @param string $expectedBrowserUa
     * @param string $expectedPlatformUa
     * @param string $expectedEngineUa
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testData(array $headers, string $expectedDeviceUa, string $expectedBrowserUa, string $expectedPlatformUa, string $expectedEngineUa): void
    {
        $result = $this->object->createRequestFromArray($headers);

        static::assertInstanceOf(GenericRequest::class, $result);

        static::assertSame($expectedDeviceUa, $result->getDeviceUserAgent(), 'device-ua mismatch');
        static::assertSame($expectedBrowserUa, $result->getBrowserUserAgent(), 'browser-ua mismatch');
        static::assertSame($expectedPlatformUa, $result->getPlatformUserAgent(), 'platform-ua mismatch');
        static::assertSame($expectedEngineUa, $result->getEngineUserAgent(), 'engine-ua mismatch');
    }

    /**
     * @throws \Symfony\Component\Finder\Exception\DirectoryNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     *
     * @return array[]
     */
    public function providerUa(): array
    {
        $path = 'tests/data';

        if (!file_exists($path)) {
            return [];
        }

        $finder = new Finder();
        $finder->files();
        $finder->name('*.json');
        $finder->ignoreDotFiles(true);
        $finder->ignoreVCS(true);
        $finder->sortByName();
        $finder->ignoreUnreadableDirs();
        $finder->in($path);

        $allData = [];

        foreach ($finder as $file) {
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            $content = $file->getContents();

            if ('' === $content || PHP_EOL === $content) {
                throw new \UnexpectedValueException('empty content');
            }

            try {
                $data = (new Json())->decode(
                    $content,
                    true
                );
            } catch (DecodeErrorException $e) {
                throw new \UnexpectedValueException('invalid content', 0, $e);
            }

            if (!is_array($data)) {
                throw new \UnexpectedValueException('no array content');
            }

            $allData = array_merge($allData, $data);
        }

        return $allData;
    }
}
