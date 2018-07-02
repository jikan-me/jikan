<?php

namespace JikanTest\Parser\Character;

use Goutte\Client;
use Jikan\Model\VoiceActor;
use Jikan\Parser\Character\CharacterListItemParser;
use PHPUnit\Framework\TestCase;

/**
 * Class CharacterListItemParserTest
 */
class CharacterListItemParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Character\CharacterListItemParser
     */
    private $parser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/35073/Overlord_II/characters');

        $this->parser = new CharacterListItemParser(
            $crawler->filterXPath('//h2[text()="Characters & Voice Actors"]/following-sibling::table[1]')
        );
    }

    /**
     * @test
     * @vcr CharactersParserTest.yaml
     */
    public function it_gets_the_mal_id()
    {
        self::assertEquals(116275, $this->parser->getMalId());
    }

    /**
     * @test
     * @vcr CharactersParserTest.yaml
     */
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/character/116275/Albedo', $this->parser->getCharacterUrl());
    }

    /**
     * @test
     * @vcr CharactersParserTest.yaml
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/r/23x32/images/characters/5/288167.jpg?s=4eb5561fa112e46f87456377a9a997ce',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     * @vcr CharactersParserTest.yaml
     */
    public function it_gets_the_voice_actors()
    {
        $voiceActors = $this->parser->getVoiceActors();
        self::assertContainsOnly(VoiceActor::class, $voiceActors);
        self::assertCount(2, $voiceActors);
        self::assertContains('Hara, Yumi', $voiceActors);
        self::assertEquals('Japanese', $voiceActors[0]->getLanguage());
        self::assertEquals('English', $voiceActors[1]->getLanguage());
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/r/23x32/images/voiceactors/3/49242.jpg?s=8fafa056dafe96209a6757ff802b7f8f',
            $voiceActors[1]->getImageUrl()
        );
        self::assertContains('Maxwell, Elizabeth', $voiceActors);
    }
}
