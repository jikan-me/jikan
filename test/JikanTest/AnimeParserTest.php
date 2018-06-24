<?php

use PHPUnit\Framework\TestCase;

/**
 * Class AnimeParserTest
 */
class AnimeParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime
     */
    private $parser;

    public function setUp()
    {
        $request = new \Jikan\Request\AnimeRequest(21);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Anime($crawler);
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
    public function it_gets_the_anime_url()
    {
        self::assertEquals('https://myanimelist.net/anime/21/One_Piece', $this->parser->getAnimeURL());
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
            $this->parser->getAnimeEpisodesUnknown()
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
            $this->parser->getAnimeAiring()
        );
    }
}
