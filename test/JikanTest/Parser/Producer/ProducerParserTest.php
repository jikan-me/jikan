<?php

namespace JikanTest\Parser\Person;

use Goutte\Client;
use Jikan\Parser\Producer\ProducerParser;
use PHPUnit\Framework\TestCase;

/**
 * Class ProducerParserTest
 */
class ProducerParserTest extends TestCase
{
    /**
     * @var ProducerParser
     */
    private $parser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/producer/1');
        $this->parser = new ProducerParser($crawler);
    }

    /**
     * @test
     * @vcr ProducerParserTest.yaml
     */
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertInstanceOf(\Jikan\Model\Common\MalUrl::class, $url);
    }

    /**
     * @test
     * @vcr ProducerParserTest.yaml
     */
    public function it_gets_anime()
    {
        $anime = $this->parser->getProducerAnime();
        self::assertCount(100, $anime);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\AnimeCard::class, $anime);
    }
}
