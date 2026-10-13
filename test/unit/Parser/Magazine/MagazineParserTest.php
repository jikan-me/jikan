<?php

namespace JikanTest\Parser\Magazine;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Magazine\MagazineParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
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
        self::assertCount(100, $manga);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MangaCard::class, $manga);
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
