<?php

namespace JikanTest\Parser\Character;

use Goutte\Client;
use Jikan\Model\Character\VoiceActor;
use Jikan\Parser\Character\CharacterListItemParser;
use JikanTest\TestCase;
use Symfony\Component\DomCrawler\Crawler;

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

        $client = new Client($this->httpClient);
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

    /**
     * @test
     */
    public function it_gets_the_mal_id()
    {
        self::assertEquals(116275, $this->parser->getMalId());
    }

    /**
     * @test
     */
    public function it_gets_the_name()
    {
        self::assertEquals('Albedo', $this->parser->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/character/116275/Albedo', $this->parser->getCharacterUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/characters/14/292046.jpg?s=db3bc7bfe5c676d984e469f0537d08bb',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_voice_actors()
    {
        $voiceActors = $this->parser->getVoiceActors();
        self::assertContainsOnly(VoiceActor::class, $voiceActors);
        self::assertCount(6, $voiceActors);
        self::assertEquals('Hara, Yumi', $voiceActors[0]->getPerson()->getName());
        self::assertEquals('Japanese', $voiceActors[0]->getLanguage());
        self::assertEquals('Maxwell, Elizabeth', $voiceActors[1]->getPerson()->getName());
        self::assertEquals('English', $voiceActors[1]->getLanguage());
        self::assertEquals(
            'https://cdn.myanimelist.net/images/voiceactors/3/49242.jpg?s=7a7f209e6414f65664f03d8207372870',
            $voiceActors[1]->getPerson()->getImages()->getJpg()->getImageUrl()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_favorites_count()
    {
        self::assertEquals(
            7606,
            $this->parser->getFavorites()
        );
    }
}
