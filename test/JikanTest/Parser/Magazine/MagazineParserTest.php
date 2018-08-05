<?php

namespace JikanTest\Parser\Magazine;

use Goutte\Client;
use Jikan\Parser\Magazine\MagazineParser;
use PHPUnit\Framework\TestCase;

/**
 * Class MagazineParserTest
 */
class MagazineParserTest extends TestCase
{
    /**
     * @var MagazineParser
     */
    private $parser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/magazine/1');
        $this->parser = new MagazineParser($crawler);
    }

    /**
     * @test
     * @vcr MagazineParserTest.yaml
     */
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertInstanceOf(\Jikan\Model\Common\MalUrl::class, $url);
    }

    /**
     * @test
     * @vcr MagazineParserTest.yaml
     */
    public function it_gets_manga()
    {
        $manga = $this->parser->getMagazineManga();
        self::assertCount(56, $manga);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MangaCard::class, $manga);
    }
}
