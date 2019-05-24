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
namespace UaRequestTest\Header;

use PHPUnit\Framework\TestCase;
use UaRequest\Header\XUcbrowserUa;

final class XUcbrowserUaTest extends TestCase
{
    /**
     * @dataProvider providerUa
     *
     * @param string $ua
     * @param bool   $hasDeviceInfo
     * @param bool   $hasBrowserInfo
     * @param bool   $hasPlatformInfo
     * @param bool   $hasEngineInfo
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testData(string $ua, bool $hasDeviceInfo, bool $hasBrowserInfo, bool $hasPlatformInfo, bool $hasEngineInfo): void
    {
        $header = new XUcbrowserUa($ua);

        self::assertSame($ua, $header->getValue(), 'header mismatch');
        self::assertSame($hasDeviceInfo, $header->hasDeviceInfo(), 'device info mismatch');
        self::assertSame($hasBrowserInfo, $header->hasBrowserInfo(), 'browser info mismatch');
        self::assertSame($hasPlatformInfo, $header->hasPlatformInfo(), 'platform info mismatch');
        self::assertSame($hasEngineInfo, $header->hasEngineInfo(), 'engine info mismatch');
    }

    /**
     * @return array[]
     */
    public function providerUa(): array
    {
        return [
            ['pf(Linux);la(en-US);re(AppleWebKit/534.31 (KHTML, like Gecko));dv(Lenovo A369i Build/JDQ39);pr(UCBrowser/9.1.0.297);ov(Android 4.2.2);pi(480*762);ss(480*762);up(U3/0.8.0);er(U);bt(GZ);pm(1);bv(1);nm(0);im(0);sr(0);nt(3);', true, true, true, true],
            ['dv(iPh4,1);pr(UCBrowser/10.2.0.517);ov(7_1_2);ss(320x416);bt(UC);pm(0);bv(0);nm(0);im(0);nt(1);', true, true, true, false],
            ['pf(44);la(en-US);dv(iPh4,1);pr(UCBrowser);ov(8_1_2);pi(640x960);ss(320x416);er(U);bt(GJ);up();re(AppleWebKit/600.1.4.12.4 (KHTML, like Gecko));pm(0);bv(0);nm(0);im(0);nt(1);', true, true, true, true],
            ['pf(44);la(en-US);dv(iPh6,2);pr(UCBrowser);ov(8_1_2);pi(640x1136);ss(320x416);er(U);bt(GJ);up();re(AppleWebKit/600.1.4.12.4 (KHTML, like Gecko));pm(0);bv(0);nm(0);im(0);nt(2);', true, true, true, true],
            ['pf(Symbian);er(U);la(zh-CN);up(U2/1.0.0);re(U2/1.0.0);dv(NokiaE72-1);pr(UCBrowser/8.9.0.253);ov(S60V3);pi(320*240);ss(320*240);bt(UC);pm(1);bv(0);nm(0);im(0);sr(2);nt(2)', true, true, true, true],
            ['pf(Linux);la(en-US);re(U2/1.0.0);dv(GT-S7262);pr(UCBrowser/9.4.1.482);ov(4.1.2);pi(480*800);ss(480*800);up(U2/1.0.0);er(U);bt(GJ);pm(1);nm(0);im(0);sr(2);nt(99);', true, true, true, true],
            ['pf(Windows);la(en-US);re(U2/1.0.0);dv(NOKIA Lumia 710);pr(UCBrowser/3.2.0.340);ov(wds 7.10);pi(480*800);ss(480*800);er(U);bt(GJ);nm(0);im(0);sr(0);nt(2);up(U2/1.0.0);pm(1);', true, true, true, true],
            ['pf(Windows);la(en-US);re(U2/1.0.0);dv(NOKIA Lumia 710);pr(UCBrowser/3.2.0.340);ov(wds 7.10);pi(480*800);ss(480*800);er(U);bt(GJ);nm(0);im(0);sr(2);nt(1);up(U2/1.0.0);pm(1);', true, true, true, true],
            ['pf(Windows);la(en-US);re(U2/1.0.0);dv(NOKIA Lumia 800);pr(UCBrowser/3.2.0.340);ov(wds 7.10);pi(480*800);ss(480*800);er(U);bt(GJ);nm(0);im(0);sr(0);nt(2);up(U2/1.0.0);pm(1);', true, true, true, true],
            ['pf(Linux);la(en-US);re(U2/1.0.0);dv(Micromax_A36);pr(UCBrowser/9.4.0.460);ov(2.3.5);pi(480*800);ss(480*800);up(U2/1.0.0);er(U);bt(GJ);pm(1);nm(0);im(0);sr(2);nt(99);', true, true, true, true],
            ['pf(Linux);la(en-US);re(U2/1.0.0);dv(GT-S7562);pr(UCBrowser/9.4.0.460);ov(4.0.4);pi(480*800);ss(480*800);up(U2/1.0.0);er(U);bt(GJ);pm(1);nm(0);im(0);sr(2);nt(99);', true, true, true, true],
            ['pf(Linux);la(en-US);re(U2/1.0.0);dv(Nokia_X);pr(UCBrowser/9.4.0.460);ov(4.1.2);pi(480*800);ss(480*800);up(U2/1.0.0);er(U);bt(GJ);pm(1);nm(0);im(0);sr(2);nt(99);', true, true, true, true],
            ['pf(Linux);la(en-US);re(U2/1.0.0);dv(KYY21);pr(UCBrowser/9.5.1.494);ov(4.2.2);pi(720*1280);ss(720*1280);up(U2/1.0.0);er(U);bt(GJ);pm(1);nm(0);im(0);sr(2);nt(99);', true, true, true, true],
            ['pf(Linux);la(en-US);re(U2/1.0.0);dv(TECNO_S3);pr(UCBrowser/8.8.1.359);ov(4.2.2);pi(320*480);ss(320*480);up(U2/1.0.0);er(U);bt(GJ);pm(1);nm(0);im(0);sr(2);nt(99);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(Nokia200);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(320*240);ss(320*240);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);', true, true, true, true],
            ['pf(Java);la(pl-PL);re(U2/1.0.0);dv(NokiaC2-01);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(SAMSUNG-GT-C3312);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(j2me);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);', false, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(sonyericssonj108i);pr(UCBrowser/8.8.0.227);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(Nokia501);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(NokiaC7-00);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(360*640);ss(360*640);up(U2/1.0.0);er(U);bt(GJ);pm(0);bv(0);nm(1);im(0);sr(0);nt(99);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(SAMSUNG-GT-S3850);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*260);ss(240*260);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);', true, true, true, true],
            ['pf(Java);la(ru);re(U2/1.0.0);dv(SAMSUNG-GT-S5250);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*400);ss(240*400);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(SAMSUNG-GT-S8600);pr(UCBrowser/9.5.0.449);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(1);sr(0);nt(99);', true, true, true, true],
            ['pf(Symbian);er(U);la(en-US);up(U2/1.0.0);re(U2/1.0.0);dv(NOKIA6120c);pr(UCBrowser/9.2.0.336);ov(S60V3);pi(240*320);ss(240*320);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(2);nt(1)', true, true, true, true],
            ['pf(Symbian);er(U);la(en-US);up(U2/1.0.0);re(U2/1.0.0);dv(NokiaE7-00);pr(UCBrowser/9.2.0.336);ov(S60V5);pi(360*640);ss(360*640);bt(GJ);pm(0);bv(0);nm(1);im(0);sr(2);nt(2)', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(Jblend);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(Nokia501s);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(Nokia501);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);', true, true, true, true],
            ['pf(Java);la(en);re(U2/1.0.0);dv(Nokia501);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);', true, true, true, true],
            ['pf(Java);la(fr-FR);re(U2/1.0.0);dv(Nokia503s);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(1);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(NokiaAsha230DualSIM);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(1);im(0);sr(0);nt(1);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(samsung-gt-s5380d);pr(UCBrowser/9.2.0.311);ov(MIDP-2.0);pi(320*480);ss(320*480);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(SAMSUNG-GT-S5380K);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(320*480);ss(320*480);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(SAMSUNG-GT-S5253);pr(UCBrowser/9.3.0.326);ov(MIDP-2.0);pi(240*400);ss(240*400);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(j2me);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(320*240);ss(320*240);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);', false, true, true, true],
            ['pf(Java);la(id);re(U2/1.0.0);dv(TCL-C616);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(240*320);ss(240*320);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);', true, true, true, true],
            ['pf(Java);la(zh-CN);re(U2/1.0.0);dv(j2me);pr(UCBrowser/9.0.0.261);ov(MIDP-2.0);pi(128*160);ss(128*160);up(U2/1.0.0);er(U);bt(UC);pm(1);bv(0);nm(2);im(1);sr(0);nt(99);', false, true, true, true],
            ['pf(42);la(zh-CN);dv(iPh3,1);pr(UCBrowser);ov(5_0_1);pi(640x960);ss(320x416);er(U);bt(UM);up();re(AppleWebKit/534.46 (KHTML, like Gecko));pm(0);bv(0);nm(0);im(0);nt(1);', true, true, true, true],
            ['pf(44);la(zh-CN);dv(iPd5,1);pr(UCBrowser);ov(7_0_2);pi(640x1136);ss(320x416);er(U);bt(UM);up();re(AppleWebKit/537.51.1 (KHTML, like Gecko));pm(0);bv(0);nm(0);im(0);nt(2);', true, true, true, true],
            ['pf(Java);la(Pt-BR);re(U2/1.0.0);dv(maui e800);pr(UCBrowser/9.2.0.311);ov(MIDP-2.0);pi(320*240);ss(320*240);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(2);im(0);sr(0);nt(99);', true, true, true, true],
            ['pf(Java);la(en-US);re(U2/1.0.0);dv(Opera);pr(UCBrowser/9.4.1.377);ov(MIDP-2.0);pi(320*480);ss(320*480);up(U2/1.0.0);er(U);bt(GJ);pm(1);bv(0);nm(0);im(0);sr(0);nt(99);', false, true, true, true],
            ['pf(Windows);la(en-US);re(U2/1.0.0);dv(NOKIA RM-914_im_mea3_380);pr(UCBrowser/3.4.1.407);ov(wds 8.0);pi(480*800);ss(480*800);er(U);bt(GJ);nm(0);im(0);sr(0);nt(1);up(U2/1.0.0);pm(1);', true, true, true, true],
            ['pf(Windows);la(en-US);re(U2/1.0.0);dv(HTC  8S by HTC);pr(UCBrowser/3.1.1.343);ov(wds 8.0);pi(480*800);ss(480*800);er(U);bt(GJ);nm(0);im(0);sr(0);nt(1);pm(0);', true, true, true, true],
            ['pf(Windows);la(zh-CN);re(U2/1.0.0);dv(HTC A620e);pr(UCBrowser/3.4.1.407);ov(wds 8.10);pi(480*800);ss(480*800);er(U);bt(UC);nm(0);im(0);sr(2);nt(2);pm(0);', true, true, true, true],
            ['pf(Windows);la(en-US);re(U2/1.0.0);dv(HTC Radar C110e);pr(UCBrowser/3.1.1.343);ov(wds 7.10);pi(480*800);ss(480*800);er(U);bt(GJ);nm(0);im(0);sr(0);nt(1);up(U2/1.0.0);pm(1);', true, true, true, true],
            ['pf(Windows);la(en-US);re(U2/1.0.0);dv(NOKIA Lumia 510);pr(UCBrowser/3.2.0.340);ov(wds 7.10);pi(480*800);ss(480*800);er(U);bt(GJ);nm(0);im(0);sr(0);nt(2);up(U2/1.0.0);pm(1);', true, true, true, true],
            ['pf(Windows);la(zh-CN);re(U2/1.0.0);dv(HTC C620e);pr(UCBrowser/3.1.1.343);ov(wds 8.0);pi(720*1280);ss(480*853);er(U);bt(UC);nm(0);im(0);sr(1);nt(2);up(U2/1.0.0);pm(1);', true, true, true, true],
            ['pf(Windows);la(en-US);re(U2/1.0.0);dv(NOKIA RM-914_eu_turkey_355);pr(UCBrowser/3.0.1.302);ov(wds 8.0);pi(480*800);ss(480*800);er(U);bt(GJ);nm(0);im(0);sr(0);nt(1);pm(0);', true, true, true, true],
        ];
    }
}
