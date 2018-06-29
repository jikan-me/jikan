<?php

namespace JikanTest\Parser\Anime;

use Jikan\Jikan;
use Jikan\Model\MalUrl;
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
     * @var \Jikan\Model\Anime
     */
    private $anime;

    public function setUp()
    {
        $request = new \Jikan\Request\Anime(21);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime\AnimeParser($crawler);

        $jikan = new Jikan();
        $this->anime = $jikan->Anime(
            $request
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_mal_id()
    {
        self::assertEquals(21, $this->parser->getAnimeID());
    }


    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_url()
    {
        self::assertEquals('https://myanimelist.net/anime/21/One_Piece', $this->parser->getAnimeURL());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_title()
    {
        self::assertEquals('One Piece', $this->parser->getAnimeTitle());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_title_english()
    {
        self::assertEquals('One Piece', $this->parser->getAnimeTitleEnglish());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_title_synonyms()
    {
        self::assertEquals('OP', $this->parser->getAnimeTitleSynonyms());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_title_japanese()
    {
        self::assertEquals('ONE PIECE', $this->parser->getAnimeTitleJapanese());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_image_url()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/anime/6/73245.jpg',
            $this->parser->getAnimeImageURL()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_synopsis()
    {
        self::assertEquals(
            'Gol D. Roger was known as the "Pirate King," the strongest and most infamous being to have sailed the Grand Line. The capture and execution of Roger by the World Government brought a change throughout the world. His last words before his death revealed the existence of the greatest treasure in the world, One Piece. It was this revelation that brought about the Grand Age of Pirates, men who dreamed of finding One Piece—which promises an unlimited amount of riches and fame—and quite possibly the pinnacle of glory and the title of the Pirate King. Enter Monkey D. Luffy, a 17-year-old boy who defies your standard definition of a pirate. Rather than the popular persona of a wicked, hardened, toothless pirate ransacking villages for fun, Luffy’s reason for being a pirate is one of pure wonder: the thought of an exciting adventure that leads him to intriguing people and ultimately, the promised treasure. Following in the footsteps of his childhood hero, Luffy and his crew travel across the Grand Line, experiencing crazy adventures, unveiling dark mysteries and battling strong enemies, all in order to reach the most coveted of all fortunes—One Piece. [Written by MAL Rewrite]',
            $this->parser->getAnimeSynopsis()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_type()
    {
        self::assertEquals(
            'TV',
            $this->parser->getAnimeType()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_episodes()
    {
        self::assertEquals(
            0,
            $this->parser->getAnimeEpisodes()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_episodes_unknown()
    {
        self::assertEquals(
            true,
            $this->anime->getEpisodesUnknown()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_status()
    {
        self::assertEquals(
            'Currently Airing',
            $this->parser->getAnimeStatus()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_airing()
    {
        self::assertEquals(
            true,
            $this->anime->isAiring()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_aired_string()
    {
        self::assertEquals(
            'Oct 20, 1999 to ?',
            $this->anime->getAiredString()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_aired()
    {
        $aired = $this->anime->getAired();
        self::assertInstanceOf(\DateTimeImmutable::class, $aired->getFrom());
        self::assertEquals('1999-10-20', $aired->getFrom()->format('Y-m-d'));
        self::assertNull($aired->getUntil());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_premiered()
    {
        self::assertEquals("Fall 1999", $this->anime->getPremiered());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_broadcast()
    {
        self::assertEquals("Sundays at 09:30 (JST)", $this->anime->getBroadcast());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_producer()
    {
        $producers = $this->anime->getProducers();
        self::assertCount(3, $producers);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\MalUrl::class, $producers);
        self::assertContains('Fuji TV', $producers);
        self::assertContains('TAP', $producers);
        self::assertContains('Shueisha', $producers);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_licensor()
    {
        $licensors = $this->anime->getLicensors();
        self::assertCount(2, $licensors);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\MalUrl::class, $licensors);
        self::assertContains('Funimation', $licensors);
        self::assertContains('4Kids Entertainment', $licensors);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_studio()
    {
        $studios = $this->anime->getStudios();
        self::assertCount(1, $studios);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\MalUrl::class, $studios);
        self::assertContains('Toei Animation', $studios);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_source()
    {
        self::assertEquals('Manga', $this->anime->getSource());
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_genre()
    {
        $genres = $this->anime->getGenres();
        self::assertCount(7, $genres);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\MalUrl::class, $genres);
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
    public function it_gets_the_anime_duration()
    {
        self::assertEquals(
            '24 min',
            $this->anime->getDuration()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_rating()
    {
        self::assertEquals(
            'PG-13 - Teens 13 or older',
            $this->anime->getRating()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_score()
    {
        self::assertEquals(
            8.54,
            $this->anime->getScore()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_scored_by()
    {
        self::assertEquals(
            428921,
            $this->anime->getScoredBy()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_rank()
    {
        self::assertEquals(
            89,
            $this->anime->getRank()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_popularity()
    {
        self::assertEquals(
            37,
            $this->anime->getPopularity()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_members()
    {
        self::assertEquals(
            730240,
            $this->anime->getMembers()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_favorites()
    {
        self::assertEquals(
            70500,
            $this->anime->getFavorites()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_related()
    {
        $related = $this->anime->getRelated();
        self::assertCount(45, $related);
        self::assertContainsOnly(MalUrl::class, $related);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_background()
    {
        self::assertEquals(
            'Several anime-original arcs have been adapted into light novels, and the series has inspired 40 video games as of 2016.',
            $this->anime->getBackground()
        );
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_opening()
    {
        $ops = $this->anime->getOpeningTheme();
        self::assertCount(19, $ops);
        self::assertContains('"We Are! (ウィーアー!)" by Hiroshi Kitadani (eps 1-47)', $ops);
        self::assertContains('"We Are (ウィーアー! 〜10周年Ver.〜)" by TVXQ (eps 373-394)', $ops);
    }

    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_ending()
    {
        $eds = $this->anime->getEndingTheme();
        self::assertCount(21, $eds);
        self::assertContains('"memories" by Maki Otsuki (eps 1-30)', $eds);
        self::assertContains('"We go! (ウィーゴー!)" by Hiroshi Kitadani (eps 542, 590)', $eds);
    }
}
