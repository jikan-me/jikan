<?php

namespace JikanTest\Parser\Search;

use JikanTest\ModelSnapshot;
use Jikan\Model\Common\MalUrl;
use Jikan\MyAnimeList\MalClient;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function it_gets_the_name()
    {
        self::assertEquals("Testarossa, Fate", $this->anime->getName());
    }

    #[Test]
    public function it_gets_the_image_url()
    {
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            $this->anime->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_gets_the_url()
    {
        self::assertEquals("https://myanimelist.net/character/1896/Fate_Testarossa", $this->anime->getUrl());
    }

    #[Test]
    public function it_gets_the_alternative_names()
    {
        self::assertContains('Fate T. Harlaown', $this->anime->getAlternativeNames());
        self::assertContains('Har', $this->anime->getAlternativeNames());
    }

    #[Test]
    public function it_gets_the_anime()
    {
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $this->anime->getAnime());
        self::assertEquals("Mahou Shoujo Lyrical Nanoha: Reflection", $this->anime->getAnime()[0]->getName());
        self::assertEquals("https://myanimelist.net/anime/17947/Mahou_Shoujo_Lyrical_Nanoha__Reflection", $this->anime->getAnime()[0]->getUrl());
    }

    #[Test]
    public function it_gets_the_manga()
    {
        self::assertContainsOnlyInstancesOf(MalUrl::class, $this->anime->getManga());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->search);
    }
}
