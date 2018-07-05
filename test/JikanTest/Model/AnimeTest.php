<?php /** @noinspection ALL */

/** @noinspection PhpCSValidationInspection */

namespace JikanTest\Parser\Anime;

use Jikan\Jikan;
use Jikan\Model\MalUrl;
use PHPUnit\Framework\TestCase;

/**
 * Class AnimeParserTest
 */
class AnimeTest extends TestCase
{
    /**
     * @var \Jikan\Model\Anime
     */
    private $anime;

    public function setUp()
    {
        $request = new \Jikan\Request\Anime\AnimeRequest(21);
        $jikan = new Jikan();
        $this->anime = $jikan->Anime($request);
    }


    /**
     * @test
     * @vcr AnimeParserTest.yaml
     */
    public function it_gets_the_anime_episodes_unknown(): void
    {
        self::assertEquals(true,$this->anime->isEpisodesUnknown());
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
