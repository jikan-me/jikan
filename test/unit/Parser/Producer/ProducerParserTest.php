<?php

namespace JikanTest\Parser\Producer;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Common\Url;
use Jikan\Parser\Producer\ProducerParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
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
        self::assertCount(326, $anime);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\AnimeCard::class, $anime);
    }

    #[Test]
    public function it_gets_image()
    {
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
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
            6085,
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
            326,
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

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        self::assertEquals($this->parser->getModel(), $this->parser->getModel());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser->getModel());
    }
}
