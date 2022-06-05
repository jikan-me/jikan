<?php

namespace JikanTest\Parser\Top;

use Goutte\Client;
use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Top\TopListItemParser;
use PHPUnit\Framework\TestCase;

/**
 * Class TopCharacterParserTest
 */
class TopCharacterParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopListItemParser
     */
    private $parser;

    public function setUp(): void
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/character.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(2)
        );
    }

    /**
     * @test
     */
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Levi', $url);
        self::assertEquals('https://myanimelist.net/character/45627/Levi', $url->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/characters/2/241413.jpg?s=be87b99243a15158d0c4234a2927742e',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_rank()
    {
        self::assertEquals(3, $this->parser->getRank());
    }

    /**
     * @test
     */
    public function it_gets_the_character_kanji()
    {
        self::assertEquals('リヴァイ', $this->parser->getKanjiName());
    }

    /**
     * @test
     */
    public function it_gets_the_animeography()
    {
        self::assertCount(3, $this->parser->getAnimeography());
        self::assertContainsOnlyInstancesOf(MalUrl::class, $this->parser->getAnimeography());
    }

    /**
     * @test
     */
    public function it_gets_the_mangaography()
    {
        self::assertCount(3, $this->parser->getMangaography());
        self::assertContainsOnlyInstancesOf(MalUrl::class, $this->parser->getMangaography());
    }

    /**
     * @test
     */
    public function it_gets_the_favorites()
    {
        self::assertEquals(115146, $this->parser->getFavorites());
    }
}
