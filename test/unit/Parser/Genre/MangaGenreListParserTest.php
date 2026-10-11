<?php

namespace JikanTest\Parser\Genre;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Genre\MangaGenreList;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class MangaGenreListParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Genre\MangaGenreListParser
 * (getMangaGenres). manga.php prepends an "adapted from" source block before
 * the genre sections; the parser anchors the sections by content (blocks whose
 * links are `/genre/` URLs), which this recording exercises.
 */
class MangaGenreListParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Genre\MangaGenreListParser
     */
    private $parser;

    /**
     * @var MangaGenreList
     */
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Genre\MangaGenresRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new \Jikan\Parser\Genre\MangaGenreListParser($crawler);
        $this->model = $this->parser->getModel();
    }

    #[Test]
    public function it_parses_the_genre_sections(): void
    {
        self::assertCount(18, $this->model->getGenres());
        self::assertCount(3, $this->model->getExplicitGenres());
        self::assertCount(53, $this->model->getThemes());
        self::assertCount(5, $this->model->getDemographics());
    }

    #[Test]
    public function it_anchors_genres_past_the_adapted_block(): void
    {
        // The "adapted" source block (All/Airing Now/Upcoming) must NOT leak
        // into the genres section.
        $genres = $this->model->getGenres();
        self::assertSame('Action', $genres[0]->getName());
        self::assertSame(1, $genres[0]->getMalId());
        self::assertSame(11422, $genres[0]->getCount());
        self::assertSame('Suspense', $genres[count($genres) - 1]->getName());
        self::assertSame(45, $genres[count($genres) - 1]->getMalId());
    }

    #[Test]
    public function it_parses_explicit_themes_and_demographics(): void
    {
        $explicit = $this->model->getExplicitGenres();
        self::assertSame('Ecchi', $explicit[0]->getName());
        self::assertSame('Erotica', $explicit[1]->getName());

        $themes = $this->model->getThemes();
        self::assertSame('Adult Cast', $themes[0]->getName());

        $demographics = $this->model->getDemographics();
        self::assertSame('Josei', $demographics[0]->getName());
        self::assertSame(5, count($demographics));
    }
}