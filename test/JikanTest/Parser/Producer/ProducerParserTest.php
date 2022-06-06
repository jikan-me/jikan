<?php

namespace JikanTest\Parser\Person;

use Goutte\Client;
use Jikan\Parser\Producer\ProducerParser;
use JikanTest\TestCase;

/**
 * Class ProducerParserTest
 */
class ProducerParserTest extends TestCase
{
    /**
     * @var ProducerParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/producer/1');
        $this->parser = new ProducerParser($crawler);
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
    public function it_gets_anime()
    {
        $anime = $this->parser->getResults();
        self::assertCount(278, $anime);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\AnimeCard::class, $anime);
    }
}
