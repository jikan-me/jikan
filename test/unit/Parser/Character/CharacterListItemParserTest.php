<?php

namespace JikanTest\Parser\Character;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Character\VoiceActor;
use Jikan\Parser\Character\CharacterListItemParser;
use Symfony\Component\DomCrawler\Crawler;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class CharacterListItemParserTest
 */
class CharacterListItemParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Character\CharacterListItemParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/35073/Overlord_II/characters');

        $this->parser = new CharacterListItemParser(
            $crawler->filterXPath('//div[contains(@class, "anime-character-container")]/table')
                ->reduce(
                    function (Crawler $crawler) {
                        return (bool)$crawler->filterXPath(
                            '//a[contains(@href, "https://myanimelist.net/character")]'
                        )->count();
                    }
                )->first()
        );
    }

    #[Test]
    public function it_gets_the_mal_id()
    {
        self::assertIsNumeric(116275, $this->parser->getMalId());
    }

    #[Test]
    public function it_gets_the_name()
    {
        self::assertEquals('Momonga', $this->parser->getName());
    }

    #[Test]
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/character/116281/Momonga', $this->parser->getCharacterUrl());
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
    public function it_gets_the_voice_actors()
    {
        $voiceActors = $this->parser->getVoiceActors();
        self::assertContainsOnlyInstancesOf(VoiceActor::class, $voiceActors);
        self::assertCount(9, $voiceActors);
        self::assertEquals('Hino, Satoshi', $voiceActors[0]->getPerson()->getName());
        self::assertEquals('Japanese', $voiceActors[0]->getLanguage());
        self::assertEquals('Guerrero, Chris', $voiceActors[1]->getPerson()->getName());
        self::assertEquals('English', $voiceActors[1]->getLanguage());
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            $voiceActors[1]->getPerson()->getImages()->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_gets_the_favorites_count()
    {
        self::assertEquals(
            13947,
            $this->parser->getFavorites()
        );
    }
}
