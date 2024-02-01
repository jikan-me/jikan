<?php

namespace Parser\Magazine;

use Jikan\Parser\Magazine\MagazineParser;
use JikanTest\Parser\Magazine\Client;
use TestCase;

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

        $client = new Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/magazine/1');
        $this->parser = new MagazineParser($crawler);
    }

    /**
     * @test
     */
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertInstanceOf(\Jikan\Model\Common\MalUrl::class, $url);
    }

    /**
     * @test
     */
    public function it_gets_manga()
    {
        $manga = $this->parser->getResults();
        self::assertCount(68, $manga);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MangaCard::class, $manga);
    }
}
