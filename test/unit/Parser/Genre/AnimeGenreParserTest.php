<?php

namespace JikanTest\Parser\Genre;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Common\AnimeCard;
use Jikan\Parser\Genre\AnimeGenreParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class AnimeGenreParserTest
 */
class AnimeGenreParserTest extends TestCase
{
    /**
     * @var AnimeGenreParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/genre/1');
        $this->parser = new AnimeGenreParser($crawler);
    }

    #[Test]
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertEquals("https://myanimelist.net/anime/genre/1/Action", $url);
    }

    #[Test]
    public function it_gets_anime()
    {
        $anime = $this->parser->getResults();
        self::assertContainsOnlyInstancesOf(AnimeCard::class, $anime);
    }

    #[Test]
    public function it_gets_the_count()
    {
        self::assertEquals(4369, $this->parser->getCount());
    }

    #[Test]
    public function it_gets_description()
    {
        self::assertStringContainsString(
            'Exciting action sequences take priority and significant conflicts between characters',
            $this->parser->getDescription()
        );
    }
}
