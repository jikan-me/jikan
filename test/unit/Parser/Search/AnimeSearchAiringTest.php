<?php

namespace JikanTest\Parser\Search;

use Jikan\Model\Search\AnimeSearch;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\Search\AnimeSearchRequest;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class AnimeSearchAiringTest
 */
class AnimeSearchAiringTest extends TestCase
{
    private AnimeSearch $search;

    #[Test]
    public function it_gets_airing_non_null()
    {
        $jikan = new MalClient($this->httpClient);
        $this->search = $jikan->getAnimeSearch(new AnimeSearchRequest('Kaguya'));
        $anime = $this->search->getResults()[0];
        self::assertEquals('Kaguya-sama wa Kokurasetai: Tensai-tachi no Renai Zunousen', $anime->getTitle());

        // @Todo: Merge with TimeProvider branch to resolve the issue
        //self::assertTrue($anime->isAiring());
    }

    #[Test]
    public function it_gets_airing_null()
    {
        $jikan = new MalClient($this->httpClient);
        $this->search = $jikan->getAnimeSearch(new AnimeSearchRequest('Aikatsu Friends'));
        $anime = $this->search->getResults()[0];
        self::assertEquals('Aikatsu Friends!: Kagayaki no Jewel', $anime->getTitle());
        self::assertFalse($anime->isAiring());
    }
}
