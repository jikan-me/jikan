<?php

namespace JikanTest\Corpus;

use Jikan\Model\Common\Url;
use Jikan\Parser\Anime\AnimeParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Two fields the model invariants flagged as empty in every recording:
 *
 * - the streaming-platforms block ends with a JustWatch attribution link ("Streaming platforms
 *   provided by JustWatch"), which is not a platform and carries no name — it was parsed as an
 *   extra streaming link with `''` for its name;
 * - a detail page whose sidebar has no background paragraph produced `''` instead of `null`.
 */
class StreamingLinksTest extends TestCase
{
    #[Test]
    public function it_does_not_parse_the_justwatch_attribution_link_as_a_platform(): void
    {
        $links = new AnimeParser(Variants::crawler('anime-1'))->getStreamingLinks();

        self::assertNotSame([], $links);
        self::assertContainsOnlyInstancesOf(Url::class, $links);

        foreach ($links as $link) {
            self::assertNotSame('', $link->getName(), 'A streaming link has no platform name.');
            self::assertStringNotContainsString(
                '//www.justwatch.com/',
                $link->getUrl(),
                'The JustWatch attribution link is not a streaming platform.'
            );
        }
    }

    #[Test]
    public function it_returns_null_when_the_sidebar_has_no_background(): void
    {
        $anime = (new AnimeParser(Variants::crawler('anime-5114')))->getModel();

        self::assertNull($anime->getBackground());
    }
}
