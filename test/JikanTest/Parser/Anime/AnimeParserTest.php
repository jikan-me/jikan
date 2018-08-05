<?php /** @noinspection ALL */

/** @noinspection PhpCSValidationInspection */

namespace JikanTest\Parser\Anime;

use Jikan\Model\Common\MalUrl;
use PHPUnit\Framework\TestCase;

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

    public function setUp()
    {
        $request = new \Jikan\Request\Anime\AnimeRequest(21);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime\AnimeParser($crawler);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_mal_id(): void
    {
        self::assertEquals(21, $this->parser->getId());
    }


    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_url(): void
    {
        self::assertEquals('https://myanimelist.net/anime/21/One_Piece', $this->parser->getURL());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_title(): void
    {
        self::assertEquals('One Piece', $this->parser->getTitle());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_title_english(): void
    {
        self::assertEquals('One Piece', $this->parser->getTitleEnglish());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_title_synonyms(): void
    {
        self::assertContains('OP', $this->parser->getTitleSynonyms());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_title_japanese(): void
    {
        self::assertEquals('ONE PIECE', $this->parser->getTitleJapanese());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_image_url(): void
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/anime/6/73245.jpg',
            $this->parser->getImageURL()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_synopsis(): void
    {
        self::assertEquals(
            'Gol D. Roger was known as the "Pirate King," the strongest and most infamous being to have sailed the Grand Line. The capture and execution of Roger by the World Government brought a change throughout the world. His last words before his death revealed the existence of the greatest treasure in the world, One Piece. It was this revelation that brought about the Grand Age of Pirates, men who dreamed of finding One Piece—which promises an unlimited amount of riches and fame—and quite possibly the pinnacle of glory and the title of the Pirate King. Enter Monkey D. Luffy, a 17-year-old boy who defies your standard definition of a pirate. Rather than the popular persona of a wicked, hardened, toothless pirate ransacking villages for fun, Luffy’s reason for being a pirate is one of pure wonder: the thought of an exciting adventure that leads him to intriguing people and ultimately, the promised treasure. Following in the footsteps of his childhood hero, Luffy and his crew travel across the Grand Line, experiencing crazy adventures, unveiling dark mysteries and battling strong enemies, all in order to reach the most coveted of all fortunes—One Piece. [Written by MAL Rewrite]',
            $this->parser->getSynopsis()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
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
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_episodes(): void
    {
        self::assertEquals(
            0,
            $this->parser->getEpisodes()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_status(): void
    {
        self::assertEquals(
            'Currently Airing',
            $this->parser->getStatus()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_aired_string(): void
    {
        self::assertEquals(
            'Oct 20, 1999 to ?',
            $this->parser->getAnimeAiredString()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_aired(): void
    {
        $aired = $this->parser->getAired();
        self::assertInstanceOf(\DateTimeImmutable::class, $aired->getFrom());
        self::assertEquals('1999-10-20', $aired->getFrom()->format('Y-m-d'));
        self::assertNull($aired->getUntil());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_premiered(): void
    {
        self::assertEquals('Fall 1999', $this->parser->getPremiered());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_broadcast(): void
    {
        self::assertEquals('Sundays at 09:30 (JST)', $this->parser->getBroadcast());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_producer(): void
    {
        $producers = $this->parser->getProducers();
        self::assertCount(3, $producers);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $producers);
        self::assertContains('Fuji TV', $producers);
        self::assertContains('TAP', $producers);
        self::assertContains('Shueisha', $producers);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_licensor(): void
    {
        $licensors = $this->parser->getLicensors();
        self::assertCount(2, $licensors);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $licensors);
        self::assertContains('Funimation', $licensors);
        self::assertContains('4Kids Entertainment', $licensors);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_studio(): void
    {
        $studios = $this->parser->getStudios();
        self::assertCount(1, $studios);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $studios);
        self::assertContains('Toei Animation', $studios);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_source(): void
    {
        self::assertEquals('Manga', $this->parser->getSource());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_genre(): void
    {
        $genres = $this->parser->getGenres();
        self::assertCount(7, $genres);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $genres);
        self::assertContains('Action', $genres);
        self::assertContains('Adventure', $genres);
        self::assertContains('Comedy', $genres);
        self::assertContains('Super Power', $genres);
        self::assertContains('Drama', $genres);
        self::assertContains('Fantasy', $genres);
        self::assertContains('Shounen', $genres);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_duration(): void
    {
        self::assertEquals(
            '24 min',
            $this->parser->getDuration()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
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
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_score(): void
    {
        self::assertEquals(
            8.54,
            $this->parser->getScore()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_scored_by(): void
    {
        self::assertEquals(
            428921,
            $this->parser->getScoredBy()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_rank(): void
    {
        self::assertEquals(
            89,
            $this->parser->getRank()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_popularity(): void
    {
        self::assertEquals(
            37,
            $this->parser->getPopularity()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_members(): void
    {
        self::assertEquals(
            730240,
            $this->parser->getMembers()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_favorites(): void
    {
        self::assertEquals(
            70500,
            $this->parser->getFavorites()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_related(): void
    {
        $related = $this->parser->getRelated();
        self::assertCount(6, $related);
        self::assertContainsOnlyInstancesOf(MalUrl::class, $related['Adaptation']);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_background(): void
    {
        self::assertEquals(
            'Several anime-original arcs have been adapted into light novels, and the series has inspired 40 video games as of 2016.',
            $this->parser->getBackground()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_opening(): void
    {
        $ops = $this->parser->getOpeningThemes();
        self::assertCount(19, $ops);
        self::assertContains('"We Are! (ウィーアー!)" by Hiroshi Kitadani (eps 1-47)', $ops);
        self::assertContains('"We Are (ウィーアー! 〜10周年Ver.〜)" by TVXQ (eps 373-394)', $ops);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_ending(): void
    {
        $eds = $this->parser->getEndingThemes();
        self::assertCount(21, $eds);
        self::assertContains('"memories" by Maki Otsuki (eps 1-30)', $eds);
        self::assertContains('"We go! (ウィーゴー!)" by Hiroshi Kitadani (eps 542, 590)', $eds);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_preview_video()
    {
        $preview = $this->parser->getPreview();
        self::assertEquals('https://www.youtube.com/embed/um-tFlVamOI?enablejsapi=1&wmode=opaque&autoplay=1', $preview);
    }
}
