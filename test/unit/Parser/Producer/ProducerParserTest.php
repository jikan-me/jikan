<?php

namespace JikanTest\Parser\Producer;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Common\Url;
use Jikan\Parser\Producer\ProducerParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ProducerParserTest
 */
class ProducerParserTest extends TestCase
{
    /**
     * @var ProducerParser
     */
    private ProducerParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/producer/1');
        $this->parser = new ProducerParser($crawler);
    }

    #[Test]
    public function it_gets_url()
    {
        $url = $this->parser->getUrl();
        self::assertInstanceOf(\Jikan\Model\Common\MalUrl::class, $url);
    }

    #[Test]
    public function it_gets_anime()
    {
        $anime = $this->parser->getResults();
        self::assertCount(309, $anime);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\AnimeCard::class, $anime);
    }

    #[Test]
    public function it_gets_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/s/common/company_logos/b03509d0-0a9f-4f7e-a75f-6ad2649f4cdc_600x600_i?s=668dd24cfe04283101aba66102a2c2c5',
            $this->parser->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_gets_established()
    {
        self::assertEquals(
            294364800,
            $this->parser->getEstablished()->getTimestamp()
        );
    }

    #[Test]
    public function it_gets_favorites()
    {
        self::assertEquals(
            4805,
            $this->parser->getFavorites()
        );
    }

    #[Test]
    public function it_gets_about()
    {
        self::assertStringContainsString(
            "Pierrot ぴえろ (Pierrot Co., Ltd.) is a Japanese animation studio established in May 1979 by former employees of both Tatsunoko Production and Mushi Production.",
            $this->parser->getAbout()
        );
    }

    #[Test]
    public function it_gets_count()
    {
        self::assertEquals(
            309,
            $this->parser->getAnimeCount()
        );
    }

    #[Test]
    public function it_gets_external_links()
    {
        $externalLinks = $this->parser->getExternalLinks();

        self::assertCount(
            11,
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
