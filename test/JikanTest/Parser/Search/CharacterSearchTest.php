<?php

namespace JikanTest\Parser\Search;

use Jikan\Model\Common\MalUrl;
use JikanTest\TestCase;
use Jikan\MyAnimeList\MalClient;

/**
 * Class CharacterSearchTest
 */
class CharacterSearchTest extends TestCase
{

    private $search;
    private $anime;

    public function setUp(): void
    {
        parent::setUp();

        $jikan = new MalClient($this->httpClient);
        $this->search = $jikan->getCharacterSearch(
            new \Jikan\Request\Search\CharacterSearchRequest('Fate')
        );
        $this->anime = $this->search->getResults()[0];
    }

    /**
     * @test
     */
    public function it_gets_the_name()
    {
        self::assertEquals("Testarossa, Fate", $this->anime->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_image_url()
    {
        self::assertEquals("https://myanimelist.cdn-dena.com/images/characters/4/226585.jpg?s=d234ff14c48241f52809684930d5a968", $this->anime->getImageUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_url()
    {
        self::assertEquals("https://myanimelist.net/character/1896/Fate_Testarossa", $this->anime->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_alternative_names()
    {
        self::assertContains('Fate T. Harlaown', $this->anime->getAlternativeNames());
        self::assertContains('Har', $this->anime->getAlternativeNames());
    }

    /**
     * @test
     */
    public function it_gets_the_anime()
    {
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $this->anime->getAnime());
        self::assertEquals("Mahou Shoujo Lyrical Nanoha ViVid", $this->anime->getAnime()[0]->getName());
        self::assertEquals("https://myanimelist.net/anime/25939/Mahou_Shoujo_Lyrical_Nanoha_ViVid", $this->anime->getAnime()[0]->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_manga()
    {
        self::assertContainsOnlyInstancesOf(MalUrl::class, $this->anime->getManga());
    }
}
