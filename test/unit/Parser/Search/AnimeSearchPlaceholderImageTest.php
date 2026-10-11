<?php

namespace JikanTest\Parser\Search;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class AnimeSearchPlaceholderImageTest
 *
 * Live-page proof for jikan-me/jikan#488: MyAnimeList serves the artless anime
 * "Qi Guai De Shou" (id 43535) with a placeholder thumbnail — currently
 * `https://cdn.myanimelist.net/images/qm_50.gif` in the search table — and a
 * detail page with no cover at all. The recorded page is a real 200 search
 * response; Jikan\Helper\ImagePlaceholder must normalize that placeholder to
 * null while sibling results keep their artwork.
 *
 * Note: the `na_series_<id>.png` variant named in ImagePlaceholder's measured
 * fragments could not be reproduced on any live page during recording (all
 * artless anime/manga reachable today serve qm_50.gif in list rows); this test
 * pins the live placeholder that IS served, through the same helper.
 */
class AnimeSearchPlaceholderImageTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Search\AnimeSearchParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Search\AnimeSearchRequest('Qi Guai De Shou');
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new \Jikan\Parser\Search\AnimeSearchParser($crawler);
    }

    #[Test]
    public function it_normalizes_the_artless_result_image_to_null(): void
    {
        $artless = null;
        foreach ($this->parser->getResults() as $result) {
            if ($result->getMalId() === 43535) {
                $artless = $result;
                break;
            }
        }

        self::assertNotNull($artless, 'expected the recorded page to contain the artless anime');
        self::assertSame('Qi Guai De Shou', $artless->getTitle());
        self::assertNull($artless->getImages()->getJpg()->getImageUrl());
        self::assertNull($artless->getImages()->getWebp()->getImageUrl());
    }

    #[Test]
    public function it_keeps_real_artwork_on_sibling_results(): void
    {
        $kept = null;
        foreach ($this->parser->getResults() as $result) {
            $image = $result->getImages()->getJpg()->getImageUrl();
            if ($image !== null) {
                $kept = $image;
                break;
            }
        }

        self::assertNotNull($kept, 'expected at least one sibling result with artwork');
        self::assertMatchesRegularExpression('~https://cdn\.myanimelist\.net/images/anime/.*~', $kept);
    }

    #[Test]
    public function it_parses_the_rest_of_the_artless_row(): void
    {
        $artless = null;
        foreach ($this->parser->getResults() as $result) {
            if ($result->getMalId() === 43535) {
                $artless = $result;
                break;
            }
        }

        self::assertNotNull($artless);
        self::assertSame('https://myanimelist.net/anime/43535/Qi_Guai_De_Shou', $artless->getUrl());
        self::assertSame('Movie', $artless->getType());
        self::assertSame(1, $artless->getEpisodes());
        self::assertSame(115, $artless->getMembers());
    }
}