<?php

namespace JikanTest\Parser\Character;

use Jikan\Http\HttpClientWrapper;
use Symfony\Component\DomCrawler\Crawler;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class StaffListItemParserTest
 */
class StaffListItemParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime\StaffListItemParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/35073/_/characters');

        $this->parser = new \Jikan\Parser\Anime\StaffListItemParser(
            $crawler->filterXPath('//h2[text()="Staff"]')
                ->ancestors()->nextAll()
                ->reduce(
                    function (Crawler $crawler) {
                        return (bool)$crawler->filterXPath(
                            '//a[contains(@href, "https://myanimelist.net/people")]'
                        )->count();
                    }
                )
                ->eq(9)
        );
    }

    #[Test]
    public function it_gets_the_mal_id()
    {
        self::assertEquals(37118, $this->parser->getMalId());
    }

    #[Test]
    public function it_gets_the_name()
    {
        self::assertEquals('Gou, Fumiyuki', $this->parser->getName());
    }

    #[Test]
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/people/37118/Fumiyuki_Gou', $this->parser->getUrl());
    }

    #[Test]
    public function it_gets_the_image()
    {
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            $this->parser->getImage()
        );
    }

    #[Test]
    public function it_gets_the_positions()
    {
        $positions = $this->parser->getPositions();
        self::assertCount(1, $positions);
        self::assertContains('Sound Director', $positions);
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
