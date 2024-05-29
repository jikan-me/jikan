<?php
namespace JikanTest\Parser\Anime;

use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\Title;
use JikanTest\TestCase;

/**
 * Class AnimeParserTest
 */
class AnimeParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime\AnimeParser
     */
    private $parser;

    /**
     * @var \Jikan\Model\Anime\Anime
     */
    private $anime;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Anime\AnimeRequest(6);
        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime\AnimeParser($crawler);
    }

    /**
     * @test
     */
    public function it_gets_the_anime_mal_id(): void
    {
        self::assertEquals(6, $this->parser->getId());
    }


    /**
     * @test
     */
    public function it_gets_the_anime_url(): void
    {
        self::assertEquals('https://myanimelist.net/anime/6/Trigun', $this->parser->getURL());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_title(): void
    {
        self::assertEquals('Trigun', $this->parser->getTitle());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_title_english(): void
    {
        self::assertEquals('Trigun', $this->parser->getTitleEnglish());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_title_synonyms(): void
    {
        self::assertEmpty($this->parser->getTitleSynonyms());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_title_japanese(): void
    {
        self::assertEquals('トライガン', $this->parser->getTitleJapanese());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_titles(): void
    {
        $titles = $this->parser->getTitles();
        self::assertCount(3, $titles);
        self::assertEquals(new Title('Default', 'Trigun'), $titles[0]);
        self::assertEquals(new Title('Japanese', 'トライガン'), $titles[1]);
        self::assertEquals(new Title('English', 'Trigun'), $titles[2]);
    }

    /**
     * @test
     */
    public function it_gets_the_anime_image_url(): void
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/anime/7/20310.jpg',
            $this->parser->getImageURL()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_synopsis(): void
    {
        self::assertStringContainsString(
            <<<EOT
            Vash the Stampede is the man with a $$60,000,000,000 bounty on his head. The reason: he's a merciless villain who lays waste to all those that oppose him and flattens entire cities for fun, garnering him the title "The Humanoid Typhoon." He leaves a trail of death and destruction wherever he goes, and anyone can count themselves dead if they so much as make eye contact—or so the rumors say.
            EOT,
            $this->parser->getSynopsis()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_type(): void
    {
        self::assertEquals(
            'TV',
            $this->parser->getType()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_episodes(): void
    {
        self::assertEquals(
            26,
            $this->parser->getEpisodes()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_status(): void
    {
        self::assertEquals(
            'Finished Airing',
            $this->parser->getStatus()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_aired_string(): void
    {
        self::assertEquals(
            'Apr 1, 1998 to Sep 30, 1998',
            $this->parser->getAnimeAiredString()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_aired(): void
    {
        $aired = $this->parser->getAired();
        self::assertInstanceOf(\DateTimeImmutable::class, $aired->getFrom());
        self::assertEquals('1998-04-01', $aired->getFrom()->format('Y-m-d'));
        self::assertEquals('1998-09-30', $aired->getUntil()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function it_gets_the_anime_premiered(): void
    {
        self::assertEquals('Spring 1998', $this->parser->getPremiered());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_broadcast(): void
    {
        self::assertEquals('Thursdays at 01:15 (JST)', $this->parser->getBroadcast());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_producer(): void
    {
        $producers = $this->parser->getProducers();
        self::assertCount(1, $producers);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $producers);
        $names = array_map(function ($item) {
            return $item->getName();
        }, $producers);
        self::assertContains('Victor Entertainment', $names);
    }

    /**
     * @test
     */
    public function it_gets_the_anime_licensor(): void
    {
        $licensors = $this->parser->getLicensors();
        self::assertCount(2, $licensors);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $licensors);
        $names = array_map(function ($item) {
            return $item->getName();
        }, $licensors);
        self::assertContains('Funimation', $names);
        self::assertContains('Geneon Entertainment USA', $names);
    }

    /**
     * @test
     */
    public function it_gets_the_anime_studio(): void
    {
        $studios = $this->parser->getStudios();
        self::assertCount(1, $studios);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $studios);
        $names = array_map(function ($item) {
            return $item->getName();
        }, $studios);
        self::assertContains('Madhouse', $names);
    }

    /**
     * @test
     */
    public function it_gets_the_anime_source(): void
    {
        self::assertEquals('Manga', $this->parser->getSource());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_genre(): void
    {
        $genres = $this->parser->getGenres();
        self::assertCount(5, $genres);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $genres);
        $names = array_map(function ($item) {
            return $item->getName();
        }, $genres);
        self::assertContains('Action', $names);
        self::assertContains('Adventure', $names);
        self::assertContains('Comedy', $names);
        self::assertContains('Drama', $names);
        self::assertContains('Sci-Fi', $names);
    }

    /**
     * @test
     */
    public function it_gets_the_anime_duration(): void
    {
        self::assertEquals(
            '24 min per ep',
            $this->parser->getDuration()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_rating(): void
    {
        self::assertEquals(
            'PG-13 - Teens 13 or older',
            $this->parser->getRating()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_score(): void
    {
        self::assertEquals(
            8.22,
            $this->parser->getScore()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_scored_by(): void
    {
        self::assertEquals(
            336220,
            $this->parser->getScoredBy()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_rank(): void
    {
        self::assertEquals(
            308,
            $this->parser->getRank()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_popularity(): void
    {
        self::assertEquals(
            243,
            $this->parser->getPopularity()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_members(): void
    {
        self::assertEquals(
            678927,
            $this->parser->getMembers()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_favorites(): void
    {
        self::assertEquals(
            13750,
            $this->parser->getFavorites()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_related(): void
    {
        $related = $this->parser->getRelated();
        self::assertCount(3, $related);
        self::assertContainsOnlyInstancesOf(MalUrl::class, $related['Adaptation']);
    }

    /**
     * @test
     */
    public function it_gets_the_anime_background(): void
    {
        self::assertStringContainsString(
            'The Japanese release by Victor Entertainment has different openings relating to the specific episode it\'s played on.',
            $this->parser->getBackground()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_anime_opening(): void
    {
        $ops = $this->parser->getOpeningThemes();
        self::assertCount(1, $ops);
        self::assertContains('"H.T." by Tsuneo Imahori', $ops);
    }

    /**
     * @test
     */
    public function it_gets_the_anime_ending(): void
    {
        $eds = $this->parser->getEndingThemes();
        self::assertCount(1, $eds);
        self::assertContains('"Kaze wa Mirai ni Fuku (The Wind Blows to the Future)" by AKIMA & NEOS', $eds);
    }

    /**
     * @test
     */
    public function it_gets_the_preview_video()
    {
        $preview = $this->parser->getPreview();
        self::assertEquals('https://www.youtube.com/embed/bJVyIXeUznY?enablejsapi=1&wmode=opaque&autoplay=1', $preview);
    }

    /**
     * @test
     */
    public function it_gets_the_streaming_links()
    {
        $streamingLinks = $this->parser->getStreamingLinks();
        self::assertEquals('http://www.crunchyroll.com/series-275669', $streamingLinks[0]->getUrl());
    }
}
