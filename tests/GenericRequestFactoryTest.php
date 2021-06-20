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

namespace UaRequestTest;

use ExceptionalJSON\DecodeErrorException;
use JsonClass\Json;
use Laminas\Diactoros\ServerRequestFactory;
use LogicException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use UaRequest\Constants;
use UaRequest\GenericRequest;
use UaRequest\GenericRequestFactory;
use UnexpectedValueException;

use function array_merge;
use function file_exists;
use function is_array;

use const PHP_EOL;

final class GenericRequestFactoryTest extends TestCase
{
    private GenericRequestFactory $object;

    protected function setUp(): void
    {
        $this->object = new GenericRequestFactory();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testCreateRequestFromArray(): void
    {
        $userAgent = 'testUA';
        $headers   = [Constants::HEADER_USERAGENT => $userAgent];

        $result = $this->object->createRequestFromArray($headers);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertSame($headers, $result->getHeaders());
        self::assertSame($userAgent, $result->getBrowserUserAgent());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testCreateRequestFromEmptyHeaders(): void
    {
        $headers = [];

        $result = $this->object->createRequestFromArray($headers);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertSame($headers, $result->getHeaders());
        self::assertSame('', $result->getBrowserUserAgent());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testCreateRequestFromString(): void
    {
        $userAgent = 'testUA';
        $headers   = [Constants::HEADER_USERAGENT => $userAgent];

        $result = $this->object->createRequestFromString($userAgent);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertSame($headers, $result->getHeaders());
        self::assertSame($userAgent, $result->getBrowserUserAgent());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testCreateRequestFromPsr7Message(): void
    {
        $userAgent       = 'testUA';
        $deviceUa        = 'testDeviceUa';
        $headers         = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            'HTTP_X_UCBROWSER_DEVICE_UA' => $deviceUa,
        ];
        $expectedHeaders = [
            Constants::HEADER_USERAGENT => $userAgent,
            Constants::HEADER_UCBROWSER_DEVICE_UA => $deviceUa,
        ];

        $result = $this->object->createRequestFromPsr7Message(ServerRequestFactory::fromGlobals($headers));

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertSame($expectedHeaders, $result->getHeaders());
        self::assertSame($userAgent, $result->getBrowserUserAgent());
        self::assertSame($deviceUa, $result->getDeviceUserAgent());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testCreateRequestFromInvalidString(): void
    {
        $userAgent = "Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY\nPM)";
        $resultUa  = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY-PM)';
        $headers   = [Constants::HEADER_USERAGENT => $resultUa];

        $result = $this->object->createRequestFromString($userAgent);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertSame($headers, $result->getHeaders());
        self::assertSame($resultUa, $result->getBrowserUserAgent());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testCreateRequestFromInvalidArray(): void
    {
        $userAgent = "Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY\nPM)";
        $headers   = [Constants::HEADER_HTTP_USERAGENT => $userAgent];

        $resultUa        = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY-PM)';
        $expectedHeaders = [Constants::HEADER_USERAGENT => $resultUa];

        $result = $this->object->createRequestFromArray($headers);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertSame($expectedHeaders, $result->getHeaders());
        self::assertSame($resultUa, $result->getBrowserUserAgent());
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws InvalidArgumentException
     * @throws Exception
     *
     * @dataProvider providerUa
     */
    public function testData(array $headers, string $expectedDeviceUa, string $expectedBrowserUa, string $expectedPlatformUa, string $expectedEngineUa): void
    {
        $result = $this->object->createRequestFromArray($headers);

        self::assertInstanceOf(GenericRequest::class, $result);

        self::assertSame($expectedDeviceUa, $result->getDeviceUserAgent(), 'device-ua mismatch');
        self::assertSame($expectedBrowserUa, $result->getBrowserUserAgent(), 'browser-ua mismatch');
        self::assertSame($expectedPlatformUa, $result->getPlatformUserAgent(), 'platform-ua mismatch');
        self::assertSame($expectedEngineUa, $result->getEngineUserAgent(), 'engine-ua mismatch');
    }

    /**
     * @return array<array<array<string, string>|string>>
     * @phpstan-return array<array{0: array<string, string>, 1: string, 2: string, 3: string, 4: string}>
     *
     * @throws LogicException
     * @throws RuntimeException
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
            /** @var SplFileInfo $file */
            $content = $file->getContents();

            if ('' === $content || PHP_EOL === $content) {
                throw new UnexpectedValueException('empty content');
            }

            try {
                $data = (new Json())->decode(
                    $content,
                    true
                );
            } catch (DecodeErrorException $e) {
                throw new UnexpectedValueException('invalid content', 0, $e);
            }

            if (!is_array($data)) {
                throw new UnexpectedValueException('no array content');
            }

            $allData = array_merge($allData, $data);
        }

        return $allData;
    }
}
