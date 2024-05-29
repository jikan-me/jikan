<?php

namespace JikanTest\Helper;

use Jikan\Helper\Media;
use Jikan\Helper\Parser;
use PHPUnit\Framework\TestCase;

/**
 * Class MediaTest
 */
class MediaTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_dates()
    {
        self::assertEquals(
            '1yXa8MAmocQ',
            Media::youtubeIdFromUrl('https://www.youtube.com/embed/1yXa8MAmocQ?enablejsapi=1&wmode=opaque&autoplay=1')
        );

        self::assertEquals(
            'yhNzL20gNX0',
            Media::youtubeIdFromUrl('https://www.youtube.com/embed/yhNzL20gNX0/?enablejsapi=1&wmode=opaque&autoplay=1')
        );
    }
}
