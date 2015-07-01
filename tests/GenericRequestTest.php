<?php
namespace WurflTest\Request;

use Wurfl\Request\Constants;
use Wurfl\Request\GenericRequest;
use Wurfl\Request\GenericRequestFactory;

/**
 * test case
 */
class GenericRequestTest
    extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $userAgent = 'testUA';
        $header    = array(
            Constants::HEADER_HTTP_USERAGENT => $userAgent
        );

        $object = new GenericRequest($header, $userAgent, null, false);

        self::assertSame($userAgent, $object->getUserAgent());
        self::assertSame($userAgent, $object->getUserAgentNormalized());
        self::assertSame($header, $object->getRequest());
        self::assertFalse($object->isXhtmlDevice());
        self::assertNull($object->getUserAgentProfile());
        self::assertSame(hash('sha512', $userAgent), $object->getId());
        self::assertInstanceOf('\Wurfl\Request\MatchInfo', $object->getMatchInfo());
        self::assertSame(array(), $object->getUserAgentsWithDeviceID());
    }
}
