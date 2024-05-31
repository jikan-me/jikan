<?php

namespace JikanTest\Parser\Top;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Top\TopListItemParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/character.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(2)
        );
    }

    #[Test]
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Monkey D., Luffy', $url->getTitle());
        self::assertEquals('https://myanimelist.net/character/40/Luffy_Monkey_D', $url->getUrl());
    }

    #[Test]
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/characters/9/310307.jpg?s=1422edf1e44c7b6262386330461eecfd',
            $this->parser->getImage()
        );
    }

    #[Test]
    public function it_gets_the_rank()
    {
        self::assertEquals(3, $this->parser->getRank());
    }

    #[Test]
    public function it_gets_the_character_kanji()
    {
        self::assertEquals('モンキー・D・ルフィ', $this->parser->getKanjiName());
    }

    #[Test]
    public function it_gets_the_animeography()
    {
        self::assertCount(3, $this->parser->getAnimeography());
        self::assertContainsOnlyInstancesOf(MalUrl::class, $this->parser->getAnimeography());
    }

    #[Test]
    public function it_gets_the_mangaography()
    {
        self::assertCount(3, $this->parser->getMangaography());
        self::assertContainsOnlyInstancesOf(MalUrl::class, $this->parser->getMangaography());
    }

    #[Test]
    public function it_gets_the_favorites()
    {
        self::assertEquals(121629, $this->parser->getFavorites());
    }
}
