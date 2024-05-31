<?php

namespace JikanTest\Parser\Character;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
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
        $crawler = $crawler->filterXPath('//div[contains(text(), \'Animeography\')]/../table/tr')->first();
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
        self::assertEquals(
            'https://cdn.myanimelist.net/images/anime/7/88019.jpg?s=5a069ff3bdeebefc62a334e9a3a41c18',
            $this->parser->getImage()
        );
    }

    #[Test]
    public function it_gets_the_role()
    {
        self::assertEquals('Main',$this->parser->getRole());
    }
}
