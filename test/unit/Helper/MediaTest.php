<?php

namespace JikanTest\Helper;

use Jikan\Helper\Media;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MediaTest extends TestCase
{
    #[Test]
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
