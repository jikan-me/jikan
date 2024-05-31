<?php

namespace JikanTest\Parser\Anime;


use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime\EpisodesParser($crawler);
    }

    #[Test]
    public function it_gets_episodes_last_page(): void
    {
        self::assertEquals(
            11,
            $this->parser->getLastPage()
        );
    }

    #[Test]
    public function it_gets_episode_id(): void
    {
        self::assertEquals(
            1,
            $this->parser->getEpisodes()[0]->getMalId()
        );
    }

    #[Test]
    public function it_gets_episode_title(): void
    {
        self::assertEquals(
            "I'm Luffy! The Man Who's Gonna Be King of the Pirates!",
            $this->parser->getEpisodes()[0]->getTitle()
        );
    }

    #[Test]
    public function it_gets_episode_title_japanese(): void
    {
        self::assertEquals(
            "俺はルフィ!海賊王になる男だ!",
            $this->parser->getEpisodes()[0]->getTitleJapanese()
        );
    }

    #[Test]
    public function it_gets_episode_title_romanji(): void
    {
        self::assertStringContainsString(
            "Ore wa Luffy! Kaizoku Ou ni Naru Otoko Da!",
            $this->parser->getEpisodes()[0]->getTitleRomanji()
        );
    }

    #[Test]
    public function it_gets_episode_aired(): void
    {
        self::assertInstanceOf(
            \DateTimeImmutable::class,
            $this->parser->getEpisodes()[0]->getAired()
        );
    }

    #[Test]
    public function it_gets_episode_filler(): void
    {
        self::assertEquals(
            false,
            $this->parser->getEpisodes()[0]->isFiller()
        );
    }

    #[Test]
    public function it_gets_episode_recap(): void
    {
        self::assertEquals(
            false,
            $this->parser->getEpisodes()[0]->isRecap()
        );
    }


    #[Test]
    public function it_gets_episode_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/anime/21/One_Piece/episode/1",
            $this->parser->getEpisodes()[0]->getUrl()
        );
    }

    #[Test]
    public function it_gets_episode_forum_url(): void
    {
        self::assertEquals(
            "https://myanimelist.net/forum/?topicid=43183",
            $this->parser->getEpisodes()[0]->getForumUrl()
        );
    }

    #[Test]
    public function it_gets_episodes_score(): void
    {
        self::assertEquals(
            4.3,
            $this->parser->getEpisodes()[0]->getScore()
        );
    }
}
