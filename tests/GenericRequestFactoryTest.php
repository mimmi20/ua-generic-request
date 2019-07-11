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
     * @param string $expectedEngineUa
     *
     * @return void
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
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
                'Opera/9.80 (BREW; Opera Mini/4.2.99/34.1244; U; xx) Presto/2.8.119 Version/11.10',
            ],
            [
                [
                    'user-agent' => 'UCWEB/2.0 (Java; U; MIDP-2.0; xx; TCL-C616) U2/1.0.0 UCBrowser/9.4.1.377 U2/1.0.0 Mobile UNTRUSTED/1.0',
                    'device-stock-ua' => 'NativeOperaMini(Haier;Native Opera Mini/4.2.99;id;BREW 3.1.5)',
                    'x-ucbrowser-device' => 'tcl#-C616',
                    'x-ucbrowser-device-ua' => 'Mozilla/5.0_(OneTouch-710C/710C_OMH_V1.6; U; REX/4.3;BREW/3.1.5.189; Profile/MIDP-2.0_Configuration/CLDC-1.1; 240*320; CTC/2.0)_Obigo Browser/1.14',
                    'x-ucbrowser-ua' => 'pf(Java);la(id);re(U2/1.0.0);dv(TCL-C616);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);',
                ],
                'pf(Java);la(id);re(U2/1.0.0);dv(TCL-C616);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);',
                'pf(Java);la(id);re(U2/1.0.0);dv(TCL-C616);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);',
                'pf(Java);la(id);re(U2/1.0.0);dv(TCL-C616);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);',
                'pf(Java);la(id);re(U2/1.0.0);dv(TCL-C616);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);',
            ],
            [
                [
                    'user-agent' => 'Opera/9.80 (Bada; Opera Mini/6.5/31.1395; U; xx) Presto/2.8.119 Version/11.10',
                    'device-stock-ua' => 'Mozilla/5.0 (Bada 2.0.0)',
                    'x-operamini-features' => 'advanced, routing, viewport, touch, file_system, download',
                    'x-operamini-phone-ua' => 'Mozilla/5.0 (Bada 2.0.0)',
                    'x-operamini-phone' => '? # ?',
                ],
                'Opera/9.80 (Bada; Opera Mini/6.5/31.1395; U; xx) Presto/2.8.119 Version/11.10',
                'Opera/9.80 (Bada; Opera Mini/6.5/31.1395; U; xx) Presto/2.8.119 Version/11.10',
                'Mozilla/5.0 (Bada 2.0.0)',
                'Opera/9.80 (Bada; Opera Mini/6.5/31.1395; U; xx) Presto/2.8.119 Version/11.10',
            ],
            [
                [
                    'user-agent' => 'UNTRUSTED/1.0/HS-T39_TD/1.0 Release/03.03.2011 Threadx/4.0 Mocor/W10 Browser/NF4.0 Profile/MIDP-2.0 Config/CLDC-1.1',
                    'x-ucbrowser-ua' => 'pf(Java);la(en-US);re(U2/1.0.0);dv(Jblend);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
                    'x-ucbrowser-device-ua' => '?',
                    'x-ucbrowser-device' => 'Jblend',
                ],
                'pf(Java);la(en-US);re(U2/1.0.0);dv(Jblend);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
                'pf(Java);la(en-US);re(U2/1.0.0);dv(Jblend);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
                'pf(Java);la(en-US);re(U2/1.0.0);dv(Jblend);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
                'pf(Java);la(en-US);re(U2/1.0.0);dv(Jblend);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
            ],
            [
                [
                    'user-agent' => 'UCWEB/2.0 (Java; U; MIDP-2.0; xx; Nokia501) U2/1.0.0 UCBrowser/9.5.0.449 U2/1.0.0 Mobile UNTRUSTED/1.0',
                    'x-ucbrowser-ua' => 'pf(Java);la(en-US);re(U2/1.0.0);dv(Nokia501);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);',
                    'x-ucbrowser-device-ua' => '?',
                    'x-ucbrowser-device' => 'nokia#501',
                ],
                'pf(Java);la(en-US);re(U2/1.0.0);dv(Nokia501);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);',
                'pf(Java);la(en-US);re(U2/1.0.0);dv(Nokia501);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);',
                'pf(Java);la(en-US);re(U2/1.0.0);dv(Nokia501);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);',
                'pf(Java);la(en-US);re(U2/1.0.0);dv(Nokia501);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);',
            ],
            [
                [
                    'user-agent' => 'NokiaC2-01/5.0 (11.40) Profile/MIDP-2.1 Configuration/CLDC-1.1 UCWEB/2.0 (Java; U; MIDP-2.0; xx; NokiaC2-01) U2/1.0.0 UCBrowser/9.5.0.449 U2/1.0.0 Mobile UNTRUSTED/1.0',
                    'x-ucbrowser-ua' => 'pf(Java);la(pl-PL);re(U2/1.0.0);dv(NokiaC2-01);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);',
                    'x-ucbrowser-device-ua' => '?',
                    'x-ucbrowser-device' => 'nokia#C2-01',
                ],
                'pf(Java);la(pl-PL);re(U2/1.0.0);dv(NokiaC2-01);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);',
                'pf(Java);la(pl-PL);re(U2/1.0.0);dv(NokiaC2-01);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);',
                'pf(Java);la(pl-PL);re(U2/1.0.0);dv(NokiaC2-01);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);',
                'pf(Java);la(pl-PL);re(U2/1.0.0);dv(NokiaC2-01);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);',
            ],
            [
                [
                    'user-agent' => 'Mozilla/5.0 (X11; U; Linux x86_64; xx) AppleWebKit/537.36 (KHTML, like Gecko)  Chrome/30.0.1599.114 Safari/537.36 Puffin/4.0.4.931AP',
                    'x-puffin-ua' => 'Android/D6503/1080x1776',
                ],
                'Android/D6503/1080x1776',
                'Mozilla/5.0 (X11; U; Linux x86_64; xx) AppleWebKit/537.36 (KHTML, like Gecko)  Chrome/30.0.1599.114 Safari/537.36 Puffin/4.0.4.931AP',
                'Android/D6503/1080x1776',
                'Mozilla/5.0 (X11; U; Linux x86_64; xx) AppleWebKit/537.36 (KHTML, like Gecko)  Chrome/30.0.1599.114 Safari/537.36 Puffin/4.0.4.931AP',
            ],
            [
                [
                    'user-agent' => 'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; NOKIA; Lumia 625)',
                    'baidu-flyflow' => 'Microsoft Windows CE 8.10.14219.0;4.0.30508.0;NOKIA;RM-941_eu_belarus_russia_215;d0be80b1a6380df0429cef6d36f56a9b318115fe;1.0.3.3',
                ],
                'Microsoft Windows CE 8.10.14219.0;4.0.30508.0;NOKIA;RM-941_eu_belarus_russia_215;d0be80b1a6380df0429cef6d36f56a9b318115fe;1.0.3.3',
                'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; NOKIA; Lumia 625)',
                'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; NOKIA; Lumia 625)',
                'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; NOKIA; Lumia 625)',
            ],
            [
                [
                    'user-agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_2 like Mac OS X; xx) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11D257 UCBrowser/10.2.0.517 Mobile',
                    'x-ucbrowser-ua' => 'dv(iPh4,1);pr(UCBrowser/10.2.0.517);ov(7_1_2);ss(320x416);bt(UC);pm(0);bv(0);nm(0);im(0);nt(1);',
                ],
                'dv(iPh4,1);pr(UCBrowser/10.2.0.517);ov(7_1_2);ss(320x416);bt(UC);pm(0);bv(0);nm(0);im(0);nt(1);',
                'dv(iPh4,1);pr(UCBrowser/10.2.0.517);ov(7_1_2);ss(320x416);bt(UC);pm(0);bv(0);nm(0);im(0);nt(1);',
                'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_2 like Mac OS X; xx) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11D257 UCBrowser/10.2.0.517 Mobile',
                'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_2 like Mac OS X; xx) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11D257 UCBrowser/10.2.0.517 Mobile',
            ],
            [
                [
                    'user-agent' => 'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; xx) AppleWebKit/534.1+ (KHTML, Like Gecko) Version/6.0.0.141 Mobile Safari/534.1+',
                    'x-ucbrowser-phone' => 'sunmicro',
                    'x-ucbrowser-phone-ua' => 'sunmicro',
                ],
                'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; xx) AppleWebKit/534.1+ (KHTML, Like Gecko) Version/6.0.0.141 Mobile Safari/534.1+',
                'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; xx) AppleWebKit/534.1+ (KHTML, Like Gecko) Version/6.0.0.141 Mobile Safari/534.1+',
                'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; xx) AppleWebKit/534.1+ (KHTML, Like Gecko) Version/6.0.0.141 Mobile Safari/534.1+',
                'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; xx) AppleWebKit/534.1+ (KHTML, Like Gecko) Version/6.0.0.141 Mobile Safari/534.1+',
            ],
            [
                [
                    'user-agent' => 'UCWEB/2.0(Java; U; MIDP-2.0; xx; gt-s5233s) U2/1.0.0 UCBrowser/8.7.1.234 U2/1.0.0 Mobile UNTRUSTED/1.0',
                    'x-ucbrowser-phone' => 'gt-s5233s',
                    'x-ucbrowser-phone-ua' => 'gt-s5233s',
                ],
                'gt-s5233s',
                'UCWEB/2.0(Java; U; MIDP-2.0; xx; gt-s5233s) U2/1.0.0 UCBrowser/8.7.1.234 U2/1.0.0 Mobile UNTRUSTED/1.0',
                'UCWEB/2.0(Java; U; MIDP-2.0; xx; gt-s5233s) U2/1.0.0 UCBrowser/8.7.1.234 U2/1.0.0 Mobile UNTRUSTED/1.0',
                'UCWEB/2.0(Java; U; MIDP-2.0; xx; gt-s5233s) U2/1.0.0 UCBrowser/8.7.1.234 U2/1.0.0 Mobile UNTRUSTED/1.0',
            ],
            [
                [
                    'user-agent' => 'SonyEricssonJ108i/R7EA Profile/MIDP-2.1 Configuration/CLDC-1.1 UNTRUSTED/1.0 UCWEB/2.0(Java; U; MIDP-2.0; xx; sonyericssonj108i) U2/1.0.0 UCBrowser/8.8.0.227 U2/1.0.0 Mobile',
                    'x-ucbrowser-phone' => 'sonyericssonj108i',
                    'x-ucbrowser-phone-ua' => 'sonyericssonj108i',
                    'x-ucbrowser-ua' => 'pf(Java);la(en-US);re(U2/1.0.0);dv(sonyericssonj108i);pr(UCBrowser/8.8.0.227);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
                ],
                'pf(Java);la(en-US);re(U2/1.0.0);dv(sonyericssonj108i);pr(UCBrowser/8.8.0.227);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
                'pf(Java);la(en-US);re(U2/1.0.0);dv(sonyericssonj108i);pr(UCBrowser/8.8.0.227);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
                'pf(Java);la(en-US);re(U2/1.0.0);dv(sonyericssonj108i);pr(UCBrowser/8.8.0.227);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
                'pf(Java);la(en-US);re(U2/1.0.0);dv(sonyericssonj108i);pr(UCBrowser/8.8.0.227);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);',
            ],
            [
                [
                    'user-agent' => 'Opera/9.80 (BREW; Opera Mini/5.1/27.2338; U; xx) Presto/2.8.119 320X240 Pantech TXT8045',
                    'x-operamini-phone' => 'Pantech # TXT8045',
                    'x-operamini-phone-ua' => 'Pantech TXT8045',
                    'x-operamini-features' => 'advanced, download',
                ],
                'Pantech TXT8045',
                'Opera/9.80 (BREW; Opera Mini/5.1/27.2338; U; xx) Presto/2.8.119 320X240 Pantech TXT8045',
                'Opera/9.80 (BREW; Opera Mini/5.1/27.2338; U; xx) Presto/2.8.119 320X240 Pantech TXT8045',
                'Opera/9.80 (BREW; Opera Mini/5.1/27.2338; U; xx) Presto/2.8.119 320X240 Pantech TXT8045',
            ],
            [
                [
                    'user-agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0_1 like Mac OS X; xx) AppleWebKit/534.46 (KHTML, like Gecko) Mobile/9A405 UCBrowser/9.1.0.287 Mobile',
                    'x-ucbrowser-ua' => 'pf(42);la(zh-CN);dv(iPh3,1);pr(UCBrowser);ov(5_0_1);pi(640x960);ss(320x416);er(U);bt(UM);up();re(AppleWebKit/534.46 (KHTML, like Gecko));pm(0);bv(0);nm(0);im(0);nt(1);',
                ],
                'pf(42);la(zh-CN);dv(iPh3,1);pr(UCBrowser);ov(5_0_1);pi(640x960);ss(320x416);er(U);bt(UM);up();re(AppleWebKit/534.46 (KHTML, like Gecko));pm(0);bv(0);nm(0);im(0);nt(1);',
                'pf(42);la(zh-CN);dv(iPh3,1);pr(UCBrowser);ov(5_0_1);pi(640x960);ss(320x416);er(U);bt(UM);up();re(AppleWebKit/534.46 (KHTML, like Gecko));pm(0);bv(0);nm(0);im(0);nt(1);',
                'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0_1 like Mac OS X; xx) AppleWebKit/534.46 (KHTML, like Gecko) Mobile/9A405 UCBrowser/9.1.0.287 Mobile',
                'pf(42);la(zh-CN);dv(iPh3,1);pr(UCBrowser);ov(5_0_1);pi(640x960);ss(320x416);er(U);bt(UM);up();re(AppleWebKit/534.46 (KHTML, like Gecko));pm(0);bv(0);nm(0);im(0);nt(1);',
            ],
            [
                [
                    'user-agent' => 'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; HUAWEI; W2-U00)',
                    'baidu-flyflow' => 'Microsoft Windows NT 8.10.14219.0;4.0.30508.0;HUAWEI;HUAWEI W2-U00;4a1b5d7105057f0c0208d83c699276ff92cedbff;2.5.0.12',
                ],
                'Microsoft Windows NT 8.10.14219.0;4.0.30508.0;HUAWEI;HUAWEI W2-U00;4a1b5d7105057f0c0208d83c699276ff92cedbff;2.5.0.12',
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; HUAWEI; W2-U00)',
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; HUAWEI; W2-U00)',
                'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; HUAWEI; W2-U00)',
            ],
        ];
    }
}
