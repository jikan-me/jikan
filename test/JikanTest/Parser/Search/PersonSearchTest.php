<?php

namespace JikanTest\Parser\Search;

use Jikan\MyAnimeList\MalClient;
use JikanTest\TestCase;

/**
 * Class PersonSearchTest
 */
class PersonSearchTest extends TestCase
{

    private $search;
    private $person;

    public function setUp(): void
    {
        parent::setUp();

        $jikan = new MalClient($this->httpClient);
        $this->search = $jikan->getPersonSearch(
            new \Jikan\Request\Search\PersonSearchRequest('Ara')
        );
        $this->person = $this->search->getResults()[0];
    }

    /**
     * @test
     */
    public function it_gets_the_name()
    {
        self::assertEquals("Araizumi, Rui", $this->person->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_image_url()
    {
        self::assertEquals(
            "https://cdn.myanimelist.net/images/voiceactors/2/42926.jpg?s=bf7e47ee3e4a1eb93e7ef86afed1c68b",
            $this->person->getImages()->getJpg()->getImageUrl()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_url()
    {
        self::assertEquals("https://myanimelist.net/people/5159/Rui_Araizumi", $this->person->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_alternative_names()
    {
        self::assertContains('あらいず☆みるい', $this->person->getAlternativeNames());
    }
}
