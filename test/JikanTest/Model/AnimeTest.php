<?php /** @noinspection ALL */

/** @noinspection PhpCSValidationInspection */

namespace JikanTest\Parser\Anime;

use Jikan\MyAnimeList\MalClient;
use PHPUnit\Framework\TestCase;

/**
 * Class AnimeParserTest
 */
class AnimeTest extends TestCase
{
    /**
     * @var \Jikan\Model\Anime\Anime
     */
    private $anime;

    public function setUp()
    {
        $request = new \Jikan\Request\Anime\AnimeRequest(21);
        $jikan = new MalClient;
        $this->anime = $jikan->getAnime($request);
    }


    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_airing(): void
    {
        self::assertEquals(true,$this->anime->isAiring());
    }
}
