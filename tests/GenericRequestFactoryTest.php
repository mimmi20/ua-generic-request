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

use PHPUnit\Framework\TestCase;
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
     * @param array $headers
     * @param string $expectedDeviceUa
     * @param string $expectedBrowserUa
     * @param string $expectedPlatformUa
     *
     * @return void
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testData(array $headers, string $expectedDeviceUa, string $expectedBrowserUa, string $expectedPlatformUa): void
    {
        $result = $this->object->createRequestFromArray($headers);

        static::assertInstanceOf(GenericRequest::class, $result);

        static::assertSame($expectedDeviceUa, $result->getDeviceUserAgent(), 'device-ua mismatch');
        static::assertSame($expectedBrowserUa, $result->getBrowserUserAgent(), 'browser-ua mismatch');
        static::assertSame($expectedPlatformUa, $result->getPlatformUserAgent(), 'platform-ua mismatch');
    }

    /**
     * @return array[]
     */
    public function providerUa(): array
    {
        return [
            [
                [
                    'user-agent' => 'UCWEB/2.0 (Java; U; MIDP-2.0; xx; Opera) U2/1.0.0 UCBrowser/9.4.1.377 U2/1.0.0 Mobile UNTRUSTED/1.0',
                    'x-ucbrowser-device' => 'Opera',
                    'x-ucbrowser-device-ua' => 'ASTRO36_TD/v3 (MRE\\2.3.00(20480) resolution\\320480 chipset\\MT6255 touch\\1 tpannel\\1 camera\\0 gsensor\\0 keyboard\\reduced) MAUI/10A1032MP_ASTRO_W1052 Release/31.12.2010 Browser/Opera Profile/MIDP-2.0 Configuration/CLDC-1.1 Sync/SyncClient1.1 Opera/9.80 (MTK; Nucleus; Opera Mobi/4000; U; en-US) Presto/2.5.28 Version/10.10',
                    'x-ucbrowser-ua' => 'pf(Java);la(en-US);re(U2/1.0.0);dv(Opera);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(320*480);ss(320*480);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
                ],
                'ASTRO36_TD/v3 (MRE\\2.3.00(20480) resolution\\320480 chipset\\MT6255 touch\\1 tpannel\\1 camera\\0 gsensor\\0 keyboard\\reduced) MAUI/10A1032MP_ASTRO_W1052 Release/31.12.2010 Browser/Opera Profile/MIDP-2.0 Configuration/CLDC-1.1 Sync/SyncClient1.1 Opera/9.80 (MTK; Nucleus; Opera Mobi/4000; U; en-US) Presto/2.5.28 Version/10.10',
                'pf(Java);la(en-US);re(U2/1.0.0);dv(Opera);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(320*480);ss(320*480);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
                'pf(Java);la(en-US);re(U2/1.0.0);dv(Opera);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(320*480);ss(320*480);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
            ],
            [
                [
                    'user-agent' => 'UCWEB/2.0 (Java; U; MIDP-2.0; Pt-BR; maui e800) U2/1.0.0 UCBrowser/9.2.0.311 U2/1.0.0 Mobile UNTRUSTED/1.0',
                    'x-ucbrowser-device' => 'maui e800',
                    'x-ucbrowser-device-ua' => 'MOT-EX226 MIDP-2.0/CLDC-1.1 Release/31.12.2010 Browser/Opera Sync/SyncClient1.1 Profile/MIDP-2.0 Configuration/CLDC-1.1 Opera/9.80 (MTK; U; en-US) Presto/2.5.28 Version/10.10',
                    'x-ucbrowser-ua' => 'pf(Java);la(Pt-BR);re(U2/1.0.0);dv(maui e800);pr(UCBrowser/9.2.0.311);ov(MIDP-2.0);pi(320*240);ss(320*240);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);',
                ],
                'pf(Java);la(Pt-BR);re(U2/1.0.0);dv(maui e800);pr(UCBrowser/9.2.0.311);ov(MIDP-2.0);pi(320*240);ss(320*240);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);',
                'pf(Java);la(Pt-BR);re(U2/1.0.0);dv(maui e800);pr(UCBrowser/9.2.0.311);ov(MIDP-2.0);pi(320*240);ss(320*240);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);',
                'pf(Java);la(Pt-BR);re(U2/1.0.0);dv(maui e800);pr(UCBrowser/9.2.0.311);ov(MIDP-2.0);pi(320*240);ss(320*240);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);',
            ],
            [
                [
                    'user-agent' => 'Opera/9.80 (BREW; Opera Mini/4.2.99/34.1244; U; xx) Presto/2.8.119 Version/11.10',
                    'device-stock-ua' => 'NativeOperaMini(Haier;Native Opera Mini/4.2.99;id;BREW 3.1.5)',
                    'x-operamini-features' => 'httpping, advanced, download, file_system, folding',
                    'x-operamini-phone-ua' => 'NativeOperaMini(Haier;Native Opera Mini/4.2.99;id;BREW 3.1.5)',
                    'x-operamini-phone' => '? # ?',
                ],
                'Opera/9.80 (BREW; Opera Mini/4.2.99/34.1244; U; xx) Presto/2.8.119 Version/11.10',
                'NativeOperaMini(Haier;Native Opera Mini/4.2.99;id;BREW 3.1.5)',
                'NativeOperaMini(Haier;Native Opera Mini/4.2.99;id;BREW 3.1.5)',
            ],
        ];
    }
}
