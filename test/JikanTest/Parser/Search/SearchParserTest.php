<?php

namespace JikanTest\Parser\Seasonal;

use Jikan\Parser\Search\SearchParser;
use PHPUnit\Framework\TestCase;

/**
 * Class SearchParserTest
 */
class SearchParserTest extends TestCase
{
    /**
     * @var SearchParser
     */
    private $parser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime.php?q=one');
        $this->parser = new SearchParser($crawler);
    }

    /**
     * @test
     * @vcr SeasonalParserTest.yaml
     */
    public function it_gets_the_keys()
    {
        $extraData = $this->parser->getExtraData();
        self::assertNotContains('Title', $extraData);
        self::assertNotContains(' ', $extraData);
        self::assertContains('Type', $extraData);
        self::assertContains('Eps.', $extraData);
        self::assertContains('Score', $extraData);
    }
}
