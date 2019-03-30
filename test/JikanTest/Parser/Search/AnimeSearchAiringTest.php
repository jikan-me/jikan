<?php

namespace JikanTest\Parser\Search;

use Jikan\Helper\TimeProvider;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\Search\AnimeSearchRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class AnimeSearchAiringTest
 */
class AnimeSearchAiringTest extends TestCase
{
    /**
     * @test
     * @vcr AnimeSearchAiringTest.yaml
     */
    public function it_gets_airing_non_null()
    {
        $mockTime = self::createMock(TimeProvider::class);
        $mockTime
            ->method('getDateTimeImmutable')
            ->willReturn(new \DateTimeImmutable('2019-03-26 01:00:00', new \DateTimeZone('UTC')));
        $jikan = new MalClient(null, $mockTime);
        $this->search = $jikan->getAnimeSearch(new AnimeSearchRequest('Kaguya'));
        $anime = $this->search->getResults()[0];
        self::assertEquals('Kaguya-sama wa Kokurasetai: Tensai-tachi no Renai Zunousen', $anime->getTitle());
        self::assertTrue($anime->isAiring());
    }

    /**
     * @test
     * @vcr AnimeSearchAiringTest.yaml
     */
    public function it_gets_airing_null()
    {
        $jikan = new MalClient;
        $this->search = $jikan->getAnimeSearch(new AnimeSearchRequest('Aikatsu Friends'));
        $anime = $this->search->getResults()[0];
        self::assertEquals('Aikatsu Friends!', $anime->getTitle());
        self::assertTrue($anime->isAiring());
    }
}
