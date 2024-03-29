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

namespace UaRequestTest\Header;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use UaRequest\Header\DeviceStockUa;

final class DeviceStockUaTest extends TestCase
{
    /** @throws ExpectationFailedException */
    #[DataProvider('providerUa')]
    public function testData(
        string $ua,
        bool $hasDeviceInfo,
        bool $hasBrowserInfo,
        bool $hasPlatformInfo,
        bool $hasEngineInfo,
    ): void {
        $header = new DeviceStockUa($ua);

        self::assertSame($ua, $header->getValue(), 'header mismatch');
        self::assertSame($hasDeviceInfo, $header->hasDeviceInfo(), 'device info mismatch');
        self::assertSame($hasBrowserInfo, $header->hasBrowserInfo(), 'browser info mismatch');
        self::assertSame($hasPlatformInfo, $header->hasPlatformInfo(), 'platform info mismatch');
        self::assertSame($hasEngineInfo, $header->hasEngineInfo(), 'engine info mismatch');
    }

    /**
     * @return array<int, array<int, bool|string>>
     *
     * @throws void
     */
    public static function providerUa(): array
    {
        return [
            ['Mozilla/5.0 (SAMSUNG; SAMSUNG-GT-S5380D/S5380DZHLB1; U; Bada/2.0; zh-cn) AppleWebKit/534.20 (KHTML, like Gecko) Dolfin/3.0 Mobile HVGA SMM-MMS/1.2.0 OPN-B', true, false, true, true],
            ['SAMSUNG-GT-S8500', true, false, false, false],
            ['Mozilla/5.0 (Linux; U; Android 4.2.5; zh-cn; MI 2SC Build/YunOS) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30', true, false, true, true],
            ['Mozilla/5.0 (Series40; Nokia501/11.1.1/java_runtime_version=Nokia_Asha_1_1_1; Profile/MIDP-2.1 Configuration/CLDC-1.1) Gecko/20100401 S40OviBrowser/3.9.0.0.22', true, false, false, true],
            ['Mozilla/5.0 (Series40; Nokia501/11.1.1/java_runtime_version=Nokia_Asha_1_1_1; Profile/MIDP-2.1 Configuration/CLDC-1.1) Gecko/20100401 S40OviBrowser/3.1.1.0.27', true, false, false, true],
            ['Mozilla/5.0 (Bada 2.0.0)', false, false, true, false],
            ['BlackBerry9700/5.0.0.235 Profile/MIDP-2.1 Configuration/CLDC-1.1 VendorID/1', true, false, true, false],
            ['BlackBerry9300', true, false, true, false],
            ['BlackBerry8530/5.0.0.973 Profile/MIDP-2.1 Configuration/CLDC-1.1 VendorID/105', true, false, true, false],
            ['NativeOperaMini(Haier;Native Opera Mini/4.2.99;id;BREW 3.1.5)', false, true, true, false],
            ['Mozilla/5.0_(Smartfren-E781A/E2_SQID_V0.1.6; U; REX/4.3;BREW/3.1.5.189; Profile/MIDP-2.0_Configuration/CLDC-1.1; 240*320; CTC/2.0)_Obigo Browser/Q7', true, false, true, false],
            ['Mozilla/4.0 (Brew MP 1.0.2; U; en-us; Kyocera; NetFront/4.1/AMB) Sprint E4255', true, false, true, false],
            ['Mozilla/4.0 (BREW 3.1.5; U; en-us; Sanyo; NetFront/3.5.1/AMB) Sprint SCP-6760', true, false, true, false],
            ['Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5', true, false, true, true],
            ['OperaMini(MAUI_MRE;Opera Mini/4.4.31223;en)', false, true, true, false],
            ['OperaMini(Fucus/Unknown;Opera Mini/4.4.31223;en)', false, true, false, false],
            ['OperaMini(Lava-Discover135;Opera Mini/4.4.31762;en)', true, true, false, false],
            ['OperaMini(Gionee_1305;Opera Mini/4.4.31989;en)', true, true, false, false],
            ['NativeOperaMini(MRE_VER_3000;240X320;MT6256;V/;Opera Mini/6.1.27412;en)', false, true, true, false],
            ['NativeOperaMini(MTK;Native Opera Mini/4.2.1198;fr)', false, true, true, false],
            ['NativeOperaMini(MTK;Opera Mini/5.1.3119;es)', false, true, true, false],
            ['NativeOperaMini(MTK/Unknown;Opera Mini/7.0.32977;en-US)', false, true, true, false],
            ['NativeOperaMini(Spreadtrum/Unknown;Native Opera Mini/4.4.29625;pt)', false, true, false, false],
            ['NativeOperaMini(Spreadtrum/HW Version:        SC6531_OPENPHONE;Native Opera Mini/4.4.31227;en)', false, true, false, false],
            ['PhilipsX2300/W1245_V12 ThreadX_OS/4.0 MOCOR/W12 Release/11.08.2012 Browser/Dorado1.0', true, false, false, false],
            ['ReksioVRE(196683)', false, false, false, false],
            ['Motorola', false, false, false, false],
            ['Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; HTC_HD2_T8585; Windows Phone 6.5)', true, false, true, false],
            ['Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; NOKIA; Lumia 710)', true, false, true, true],
        ];
    }
}
