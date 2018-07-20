<?php

namespace JikanTest\Parser\Search;

use PHPUnit\Framework\TestCase;
use Jikan\Jikan;

/**
 * Class PersonSearchTest
 */
class PersonSearchTest extends TestCase
{

    private $search;
    private $person;

    public function setUp()
    {
        $jikan = new Jikan;
        $this->search = $jikan->PersonSearch(
            new \Jikan\Request\Search\PersonSearchRequest('Ara')
        );
        $this->person = $this->search->getResults()[0];
    }

    /**
     * @test
     * @vcr PersonSearchTest.yaml
     */
    public function it_gets_the_name()
    {
        self::assertEquals("Ara-chan", $this->person->getName());
    }

    /**
     * @test
     * @vcr PersonSearchTest.yaml
     */
    public function it_gets_the_image_url()
    {
        self::assertEquals("https://myanimelist.cdn-dena.com/r/23x32/images/characters/14/56841.jpg?s=10d5e5f7c810ad4f8e346c243478afba", $this->person->getImageUrl());
    }

    /**
     * @test
     * @vcr PersonSearchTest.yaml
     */
    public function it_gets_the_url()
    {
        self::assertInstanceOf(\Jikan\Model\Common\MalUrl::class, $this->person->getUrl());
    }

    /**
     * @test
     * @vcr PersonSearchTest.yaml
     */
    public function it_gets_the_alternative_names()
    {
        self::assertContains('Hokuto-kun', $this->person->getAlternativeNames());
    }
}
