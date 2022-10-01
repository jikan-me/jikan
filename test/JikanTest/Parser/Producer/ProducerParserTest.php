<?php

namespace JikanTest\Parser\Person;

use Goutte\Client;
use Jikan\Model\Common\Url;
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
        self::assertCount(287, $anime);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\AnimeCard::class, $anime);
    }

    /**
     * @test
     */
    public function it_gets_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/company/1.png',
            $this->parser->getImages()->getJpg()->getImageUrl()
        );
    }

    /**
     * @test
     */
    public function it_gets_established()
    {
        self::assertEquals(
            294364800,
            $this->parser->getEstablished()->getTimestamp()
        );
    }

    /**
     * @test
     */
    public function it_gets_favorites()
    {
        self::assertEquals(
            2251,
            $this->parser->getFavorites()
        );
    }

    /**
     * @test
     */
    public function it_gets_about()
    {
        self::assertEquals(
            null,
            $this->parser->getAbout()
        );
    }

    /**
     * @test
     */
    public function it_gets_count()
    {
        self::assertEquals(
            287,
            $this->parser->getAnimeCount()
        );
    }

    /**
     * @test
     */
    public function it_gets_external_links()
    {
        $externalLinks = $this->parser->getExternalLinks();

        self::assertCount(
            4,
            $externalLinks
        );

        self::assertContainsOnlyInstancesOf(
            Url::class,
            $externalLinks
        );

        self::assertEquals(
            'http://pierrot.jp/',
            $externalLinks[0]->getUrl()
        );
    }
}
