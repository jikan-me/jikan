<?php

namespace JikanTest\Parser\Character;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class AnimeographyParserTest
 */
class AnimeographyParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Character\AnimeographyParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/character/116281');
        $crawler = $crawler->filterXPath('//div[contains(text(), \'Animeography\')]/../table/tbody/tr')->first();
        $this->parser = new \Jikan\Parser\Character\AnimeographyParser($crawler);
    }

    #[Test]
    public function it_gets_the_anime_mal_id()
    {
        self::assertEquals(29803, $this->parser->getMalId());
    }

    #[Test]
    public function it_gets_the_anime_url()
    {
        self::assertEquals('https://myanimelist.net/anime/29803/Overlord', $this->parser->getUrl());
    }

    #[Test]
    public function it_gets_the_anime_name()
    {
        self::assertEquals('Overlord', $this->parser->getName());
    }

    #[Test]
    public function it_gets_the_anime_image()
    {
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            $this->parser->getImage()
        );
    }

    #[Test]
    public function it_gets_the_role()
    {
        self::assertEquals('Main',$this->parser->getRole());
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
