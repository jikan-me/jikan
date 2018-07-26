<?php

namespace JikanTest\Parser\Anime;

use PHPUnit\Framework\TestCase;

class AnimeEpisodesParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime\EpisodesParser
     */
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\Anime\AnimeEpisodesRequest(21);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime\EpisodesParser($crawler);
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     * @vcr AnimeEpisodesParserTest.yaml
     */
    public function it_gets_episodes_last_page(): void
    {
        self::assertEquals(
            9,
            $this->parser->getEpisodesLastPage()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     * @vcr AnimeEpisodesParserTest.yaml
     */
    public function it_gets_episode_id(): void
    {
        self::assertEquals(
            1,
            $this->parser->getEpisodes()[0]->getEpisodeId()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     * @vcr AnimeEpisodesParserTest.yaml
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
     * @vcr AnimeEpisodesParserTest.yaml
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
     * @vcr AnimeEpisodesParserTest.yaml
     */
    public function it_gets_episode_title_romanji(): void
    {
        self::assertContains(
            "Ore wa Luffy! Kaizoku Ou ni Naru Otoko Da!",
            $this->parser->getEpisodes()[0]->getTitleRomanji()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     * @vcr AnimeEpisodesParserTest.yaml
     */
    public function it_gets_episode_aired(): void
    {
        self::assertInstanceOf(
            \Jikan\Model\Common\DateRange::class,
            $this->parser->getEpisodes()[0]->getAired()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     * @vcr AnimeEpisodesParserTest.yaml
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
     * @vcr AnimeEpisodesParserTest.yaml
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
     * @vcr AnimeEpisodesParserTest.yaml
     */
    public function it_gets_episode_video_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/anime/21/One_Piece/episode/1",
            $this->parser->getEpisodes()[0]->getVideoUrl()
        );
    }

    /**
     * @test
     * @covers \Jikan\Parser\Anime\EpisodesParser
     * @vcr AnimeEpisodesParserTest.yaml
     */
    public function it_gets_episode_forum_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/forum/?topicid=43183",
            $this->parser->getEpisodes()[0]->getForumUrl()
        );
    }
}