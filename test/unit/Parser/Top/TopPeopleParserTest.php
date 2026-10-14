<?php

namespace JikanTest\Parser\Top;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Top\TopListItemParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class TopPeopleParserTest
 */
class TopPeopleParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopListItemParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/people.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(7)
        );
    }

    #[Test]
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Oda, Eiichiro', $url->getName());
        $url = $url->getUrl();
        $this->assertIsString($url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/people/', parse_url($url)['path']);
    }

    #[Test]
    public function it_gets_the_rank()
    {
        self::assertEquals(8, $this->parser->getRank());
    }

    #[Test]
    public function it_gets_the_favorites()
    {
        self::assertEquals(56420, $this->parser->getPeopleFavorites());
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
    public function it_gets_the_kanji_name()
    {
        self::assertSame('尾田 栄一郎', $this->parser->getKanjiName());
    }

    #[Test]
    public function it_gets_the_birthday()
    {
        self::assertEquals(
            '1975-01-01',
            $this->parser->getBirthday()->format('Y-m-d')
        );
    }
}
