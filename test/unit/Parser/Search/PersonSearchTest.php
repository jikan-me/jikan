<?php

namespace JikanTest\Parser\Search;

use JikanTest\ModelSnapshot;
use Jikan\MyAnimeList\MalClient;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function it_gets_the_name()
    {
        self::assertEquals("ARA", $this->person->getName());
    }

    #[Test]
    public function it_gets_the_image_url()
    {
        // ARA has no picture on MAL: the questionmark placeholder must not leak
        self::assertNull($this->person->getImages()->getJpg()->getImageUrl());
    }

    #[Test]
    public function it_gets_the_url()
    {
        self::assertEquals("https://myanimelist.net/people/88304/ARA", $this->person->getUrl());
    }

    #[Test]
    public function it_gets_the_alternative_names()
    {
        self::assertContains('아라', $this->person->getAlternativeNames());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->search);
    }
}
