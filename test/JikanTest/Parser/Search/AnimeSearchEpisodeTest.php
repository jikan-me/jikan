<?php

namespace JikanTest\Parser\Search;

use Jikan\MyAnimeList\MalClient;
use Jikan\Request\Search\AnimeSearchRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class AnimeSearchEpisodeTest
 */
class AnimeSearchEpisodeTest extends TestCase
{
    /**
     * @test
     * @vcr AnimeSearchEpisodeTest.yaml
     */
    public function it_gets_one_episode_airing_false()
    {
        $jikan = new MalClient;
        $this->search = $jikan->getAnimeSearch(new AnimeSearchRequest('Ijimekko Ookami to Nana-chan'));
        $anime = $this->search->getResults()[0];
        self::assertEquals('Ijimekko Ookami to Nana-chan', $anime->getTitle());
        self::assertFalse($anime->isAiring());
    }

    /**
     * @test
     * @vcr AnimeSearchEpisodeTest.yaml
     */
    public function it_gets_multiple_episodes_airing_true()
    {
        $jikan = new MalClient;
        $this->search = $jikan->getAnimeSearch(new AnimeSearchRequest('Bloody Bunny'));
        $anime = $this->search->getResults()[0];
        self::assertEquals('Bloody Bunny', $anime->getTitle());
        self::assertTrue($anime->isAiring());
    }
}
