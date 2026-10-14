<?php

namespace JikanTest\Parser\Genre;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Common\MangaCard;
use Jikan\Parser\Genre\MangaGenreParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class MangaGenreParserTest
 */
class MangaGenreParserTest extends TestCase
{
    /**
     * @var MangaGenreParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/genre/1');
        $this->parser = new MangaGenreParser($crawler);
    }

    #[Test]
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertEquals("https://myanimelist.net/manga/genre/1/Action", $url);
    }

    #[Test]
    public function it_gets_manga()
    {
        $manga = $this->parser->getResults();
        self::assertContainsOnlyInstancesOf(MangaCard::class, $manga);
    }

    #[Test]
    public function it_gets_the_count()
    {
        self::assertEquals(10956, $this->parser->getCount());
    }

    #[Test]
    public function it_gets_description()
    {
        self::assertStringContainsString(
            ' individual characters and the effort they put into their personal battles',
            $this->parser->getDescription()
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
        ModelSnapshot::assertMatches($this, $this->parser->getModel(), true);
    }
}
