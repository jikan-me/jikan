<?php

namespace JikanTest\Parser\Anime;

use PHPUnit\Framework\TestCase;

class AnimeEpisodeParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime\AnimeEpisodeParser
     */
    private $parser;

    public function setUp(): void
    {
        $request = new \Jikan\Request\Anime\AnimeEpisodeRequest(21, 1);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime\AnimeEpisodeParser($crawler);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeEpisodeParser
     * @vcr AnimeEpisodeParserTest.yaml
     */
    public function it_gets_episode_id(): void
    {
        self::assertEquals(
            1,
            $this->parser->getEpisodeId()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeEpisodeParser
     * @vcr AnimeEpisodeParserTest.yaml
     */
    public function it_gets_url(): void
    {
        self::assertEquals(
            'https://myanimelist.net/anime/21/One_Piece/episode/1',
            $this->parser->getEpisodeUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeEpisodeParser
     * @vcr AnimeEpisodeParserTest.yaml
     */
    public function it_gets_title(): void
    {
        self::assertEquals(
            'I\'m Luffy! The Man Who\'s Gonna Be King of the Pirates!',
            $this->parser->getTitle()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeEpisodeParser
     * @vcr AnimeEpisodeParserTest.yaml
     */
    public function it_gets_title_japanese(): void
    {
        self::assertEquals(
            '俺はルフィ!海賊王になる男だ!',
            $this->parser->getTitleJapanese()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeEpisodeParser
     * @vcr AnimeEpisodeParserTest.yaml
     */
    public function it_gets_title_romaji(): void
    {
        self::assertEquals(
            'Ore wa Luffy! Kaizoku Ou ni Naru Otoko Da!',
            $this->parser->getTitleRomanji()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeEpisodeParser
     * @vcr AnimeEpisodeParserTest.yaml
     */
    public function it_gets_duration(): void
    {
        self::assertEquals(
            1475,
            $this->parser->getDuration()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeEpisodeParser
     * @vcr AnimeEpisodeParserTest.yaml
     */
    public function it_gets_aired_date(): void
    {
        self::assertEquals(
            940345200,
            $this->parser->getAired()->getTimestamp()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeEpisodeParser
     * @vcr AnimeEpisodeParserTest.yaml
     */
    public function it_gets_filler(): void
    {
        self::assertEquals(
            false,
            $this->parser->getFiller()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeEpisodeParser
     * @vcr AnimeEpisodeParserTest.yaml
     */
    public function it_gets_recap(): void
    {
        self::assertEquals(
            false,
            $this->parser->getRecap()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\AnimeEpisodeParser
     * @vcr AnimeEpisodeParserTest.yaml
     */
    public function it_gets_synopsis(): void
    {
        self::assertContains(
            'Countless souls have been lured along the Grand Line in pursuit of the legendary One Piece!',
            $this->parser->getSynopsis()
        );
    }
}