<?php

namespace JikanTest\Parser\Genre;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Genre\AnimeGenreList;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class AnimeGenreListParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Genre\AnimeGenreListParser
 * (getAnimeGenres). The four sections are anchored by content (blocks whose
 * links are `/genre/` URLs) so the parser stays aligned when MAL prepends
 * page-specific `genre-link` blocks.
 */
class AnimeGenreListParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Genre\AnimeGenreListParser
     */
    private $parser;

    /**
     * @var AnimeGenreList
     */
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Genre\AnimeGenresRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new \Jikan\Parser\Genre\AnimeGenreListParser($crawler);
        $this->model = $this->parser->getModel();
    }

    #[Test]
    public function it_parses_the_genre_sections(): void
    {
        self::assertCount(18, $this->model->getGenres());
        self::assertCount(3, $this->model->getExplicitGenres());
        self::assertCount(52, $this->model->getThemes());
        self::assertCount(5, $this->model->getDemographics());
    }

    #[Test]
    public function it_parses_the_first_and_last_genres(): void
    {
        $genres = $this->model->getGenres();
        self::assertSame('Action', $genres[0]->getName());
        self::assertSame(1, $genres[0]->getMalId());
        self::assertSame('Suspense', $genres[count($genres) - 1]->getName());
        self::assertSame(41, $genres[count($genres) - 1]->getMalId());
    }

    #[Test]
    public function it_parses_genre_counts(): void
    {
        $genres = $this->model->getGenres();
        self::assertSame(5040, $genres[0]->getCount());
        $adventure = array_values(array_filter(
            $genres,
            static fn ($genre) => $genre->getMalId() === 2
        ));
        self::assertCount(1, $adventure);
        self::assertSame(4631, $adventure[0]->getCount());
    }

    #[Test]
    public function it_parses_explicit_themes_and_demographics(): void
    {
        $explicit = $this->model->getExplicitGenres();
        self::assertSame('Ecchi', $explicit[0]->getName());

        $themes = $this->model->getThemes();
        self::assertSame('Adult Cast', $themes[0]->getName());

        $demographics = $this->model->getDemographics();
        self::assertSame('Josei', $demographics[0]->getName());
        self::assertSame(43, $demographics[0]->getMalId());
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
