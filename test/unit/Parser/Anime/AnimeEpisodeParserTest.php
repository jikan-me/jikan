<?php

namespace JikanTest\Parser\Anime;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AnimeEpisodeParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime\AnimeEpisodeParser
     */
    private \Jikan\Parser\Anime\AnimeEpisodeParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Anime\AnimeEpisodeRequest(21, 1);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime\AnimeEpisodeParser($crawler);
    }

    #[Test]
    public function it_gets_episode_id(): void
    {
        self::assertEquals(
            1,
            $this->parser->getEpisodeId()
        );
    }

    #[Test]
    public function it_gets_url(): void
    {
        self::assertEquals(
            'https://myanimelist.net/anime/21/One_Piece/episode/1',
            $this->parser->getEpisodeUrl()
        );
    }

    #[Test]
    public function it_gets_title(): void
    {
        self::assertEquals(
            'I\'m Luffy! The Man Who\'s Gonna Be King of the Pirates!',
            $this->parser->getTitle()
        );
    }

    #[Test]
    public function it_gets_title_japanese(): void
    {
        self::assertEquals(
            '俺はルフィ!海賊王になる男だ!',
            $this->parser->getTitleJapanese()
        );
    }

    #[Test]
    public function it_gets_title_romaji(): void
    {
        self::assertEquals(
            'Ore wa Luffy! Kaizoku Ou ni Naru Otoko Da!',
            $this->parser->getTitleRomanji()
        );
    }

    #[Test]
    public function it_gets_duration(): void
    {
        self::assertEquals(
            1475,
            $this->parser->getDuration()
        );
    }

    #[Test]
    public function it_gets_aired_date(): void
    {
        self::assertEquals(
            940345200,
            $this->parser->getAired()->getTimestamp()
        );
    }

    #[Test]
    public function it_gets_filler(): void
    {
        self::assertEquals(
            false,
            $this->parser->getFiller()
        );
    }

    #[Test]
    public function it_gets_recap(): void
    {
        self::assertEquals(
            false,
            $this->parser->getRecap()
        );
    }

    #[Test]
    public function it_gets_synopsis(): void
    {
        self::assertStringContainsString(
            'The series begins with an attack on a cruise ship at the hands of Alvida. Coby, a slave of Alvida, discovers a barrel.',
            $this->parser->getSynopsis()
        );
    }
}
