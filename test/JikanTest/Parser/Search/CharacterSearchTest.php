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
            new \Jikan\Request\Search\CharacterSearchRequest('Testarossa')
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
        self::assertEquals(
            "https://cdn.myanimelist.net/images/characters/4/226585.jpg?s=267b6d2c90c45a5bdbc191f50d431690",
            $this->anime->getImages()->getJpg()->getImageUrl()
        );
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
        self::assertEquals("Mahou Shoujo Lyrical Nanoha: Reflection", $this->anime->getAnime()[0]->getName());
        self::assertEquals("https://myanimelist.net/anime/17947/Mahou_Shoujo_Lyrical_Nanoha__Reflection", $this->anime->getAnime()[0]->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_manga()
    {
        self::assertContainsOnlyInstancesOf(MalUrl::class, $this->anime->getManga());
    }
}
