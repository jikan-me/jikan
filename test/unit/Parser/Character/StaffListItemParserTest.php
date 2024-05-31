<?php

namespace JikanTest\Parser\Character;

use Jikan\Http\HttpClientWrapper;
use Symfony\Component\DomCrawler\Crawler;
use JikanTest\TestCase;
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
        self::assertEquals(12596, $this->parser->getMalId());
    }

    #[Test]
    public function it_gets_the_name()
    {
        self::assertEquals('Tom-H@ck', $this->parser->getName());
    }

    #[Test]
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/people/12596/Tom-Hck', $this->parser->getUrl());
    }

    #[Test]
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/voiceactors/3/33089.jpg?s=50f22657ed0a169f99eb8d18342e5486',
            $this->parser->getImage()
        );
    }

    #[Test]
    public function it_gets_the_positions()
    {
        $positions = $this->parser->getPositions();
        self::assertCount(2, $positions);
        self::assertContains('Theme Song Composition', $positions);
        self::assertContains('Theme Song Arrangement', $positions);
    }
}
