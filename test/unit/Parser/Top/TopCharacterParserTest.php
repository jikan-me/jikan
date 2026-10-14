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
        self::assertEquals('Levi', $url->getTitle());
        $url = $url->getUrl();
        $this->assertIsString($url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/character/', parse_url($url)['path']);
    }

    #[Test]
    public function it_gets_the_image()
    {
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
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
        self::assertSame('リヴァイ', $this->parser->getKanjiName());
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
        self::assertEquals(145185, $this->parser->getFavorites());
    }
}
