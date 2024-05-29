<?php

namespace JikanTest\Parser\Anime;

use JikanTest\TestCase;

class AnimeEpisodesParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime\EpisodesParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Anime\AnimeEpisodesRequest(21);
        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime\EpisodesParser($crawler);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     */
    public function it_gets_episodes_last_page(): void
    {
        self::assertEquals(
            11,
            $this->parser->getLastPage()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     */
    public function it_gets_episode_id(): void
    {
        self::assertEquals(
            1,
            $this->parser->getEpisodes()[0]->getMalId()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     */
    public function it_gets_episode_title(): void
    {
        self::assertEquals(
            "I'm Luffy! The Man Who's Gonna Be King of the Pirates!",
            $this->parser->getEpisodes()[0]->getTitle()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     */
    public function it_gets_episode_title_japanese(): void
    {
        self::assertEquals(
            "俺はルフィ!海賊王になる男だ!",
            $this->parser->getEpisodes()[0]->getTitleJapanese()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     */
    public function it_gets_episode_title_romanji(): void
    {
        self::assertStringContainsString(
            "Ore wa Luffy! Kaizoku Ou ni Naru Otoko Da!",
            $this->parser->getEpisodes()[0]->getTitleRomanji()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     */
    public function it_gets_episode_aired(): void
    {
        self::assertInstanceOf(
            \DateTimeImmutable::class,
            $this->parser->getEpisodes()[0]->getAired()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     */
    public function it_gets_episode_filler(): void
    {
        self::assertEquals(
            false,
            $this->parser->getEpisodes()[0]->isFiller()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     */
    public function it_gets_episode_recap(): void
    {
        self::assertEquals(
            false,
            $this->parser->getEpisodes()[0]->isRecap()
        );
    }


    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     */
    public function it_gets_episode_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/anime/21/One_Piece/episode/1",
            $this->parser->getEpisodes()[0]->getUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     */
    public function it_gets_episode_forum_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/forum/?topicid=43183",
            $this->parser->getEpisodes()[0]->getForumUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     */
    public function it_gets_episodes_score(): void
    {
        self::assertEquals(
            4.3,
            $this->parser->getEpisodes()[0]->getScore()
        );
    }
}
