<?php

namespace JikanTest\Parser\Anime;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class AnimePlaceholderImageTest
 *
 * Live-page proof for the jikan#488 apple-touch-icon finding: an artless anime
 * detail page (anime 43535 "Qi Guai De Shou") has no cover at all — MAL serves
 * the site icon `.../img/sp/icon/apple-touch-icon-256.png` as og:image. The
 * recorded page is a real 200 response; Jikan\Parser\Anime\AnimeParser must
 * resolve that fallback to `null` instead of surfacing the MAL icon as the
 * entry's image. The rest of the page keeps parsing normally.
 */
class AnimePlaceholderImageTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime\AnimeParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/43535');

        $this->parser = new \Jikan\Parser\Anime\AnimeParser($crawler);
    }

    #[Test]
    public function it_resolves_the_og_image_fallback_to_null(): void
    {
        self::assertNull($this->parser->getImageURL());
    }

    #[Test]
    public function it_still_parses_the_rest_of_the_page(): void
    {
        self::assertSame(43535, $this->parser->getId());
        self::assertSame('Qi Guai De Shou', $this->parser->getTitle());
        self::assertSame(
            'https://myanimelist.net/anime/43535/Qi_Guai_De_Shou',
            $this->parser->getURL()
        );
    }

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        self::assertEquals($this->parser->getModel(), $this->parser->getModel());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser->getModel());
    }
}
