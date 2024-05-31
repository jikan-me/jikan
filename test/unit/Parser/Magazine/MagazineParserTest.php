<?php

namespace JikanTest\Parser\Magazine;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Magazine\MagazineParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class MagazineParserTest
 */
class MagazineParserTest extends TestCase
{
    /**
     * @var MagazineParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/magazine/1');
        $this->parser = new MagazineParser($crawler);
    }

    #[Test]
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertInstanceOf(\Jikan\Model\Common\MalUrl::class, $url);
    }

    #[Test]
    public function it_gets_manga()
    {
        $manga = $this->parser->getResults();
        self::assertCount(68, $manga);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MangaCard::class, $manga);
    }
}
