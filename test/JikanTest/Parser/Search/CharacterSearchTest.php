<?php

namespace JikanTest\Parser\Search;

use PHPUnit\Framework\TestCase;
use Jikan\MyAnimeList\MalClient;

/**
 * Class CharacterSearchTest
 */
class CharacterSearchTest extends TestCase
{

    private $search;
    private $anime;

    public function setUp()
    {
        $jikan = new MalClient();
        $this->search = $jikan->getCharacterSearch(
            new \Jikan\Request\Search\CharacterSearchRequest('Fate')
        );
        $this->anime = $this->search->getResults()[0];
    }

    /**
     * @test
     * @vcr CharacterSearchTest.yaml
     */
    public function it_gets_the_name()
    {
        self::assertEquals("Testarossa, Fate", $this->anime->getName());
    }

    /**
     * @test
     * @vcr CharacterSearchTest.yaml
     */
    public function it_gets_the_image_url()
    {
        self::assertEquals("https://myanimelist.cdn-dena.com/images/characters/4/226585.jpg?s=d234ff14c48241f52809684930d5a968", $this->anime->getImageUrl());
    }

    /**
     * @test
     * @vcr CharacterSearchTest.yaml
     */
    public function it_gets_the_url()
    {
        self::assertEquals("https://myanimelist.net/character/1896/Fate_Testarossa", $this->anime->getUrl());
    }

    /**
     * @test
     * @vcr CharacterSearchTest.yaml
     */
    public function it_gets_the_alternative_names()
    {
        self::assertContains('Fate T. Harlaown', $this->anime->getAlternativeNames());
        self::assertContains('Har', $this->anime->getAlternativeNames());
    }

    /**
     * @test
     * @vcr CharacterSearchTest.yaml
     */
    public function it_gets_the_anime()
    {
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $this->anime->getAnime());
        self::assertEquals("Mahou Shoujo Lyrical Nanoha: The Movie 1st", $this->anime->getAnime()[0]->getName());
        self::assertEquals("https://myanimelist.net/anime/4985/Mahou_Shoujo_Lyrical_Nanoha__The_Movie_1st", $this->anime->getAnime()[0]->getUrl());
    }

    /**
     * @test
     * @vcr CharacterSearchTest.yaml
     */
    public function it_gets_the_manga()
    {
        self::assertEquals([], $this->anime->getManga());
    }
}
