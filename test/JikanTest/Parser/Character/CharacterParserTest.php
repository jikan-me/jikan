<?php

namespace JikanTest\Parser\Character;

use JikanTest\TestCase;

/**
 * Class CharacterParserTest
 */
class CharacterParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Character\CharacterParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Character\CharacterRequest(116281);
        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Character\CharacterParser($crawler);
    }

    /**
     * @test
     */
    public function it_gets_the_mal_id()
    {
        self::assertEquals(116281, $this->parser->getMalId());
    }

    /**
     * @test
     */
    public function it_gets_the_character_url()
    {
        self::assertEquals('https://myanimelist.net/character/116281/Momonga', $this->parser->getCharacterUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_name()
    {
        self::assertEquals('Momonga', $this->parser->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_name_in_kanji()
    {
        self::assertEquals('モモンガ', $this->parser->getNameKanji());
    }

    /**
     * @test
     */
    public function it_gets_the_nicknames()
    {
        $aliases = $this->parser->getNameNicknames();
        self::assertCount(2, $aliases);
        self::assertContains('Momon', $aliases);
        self::assertContains('Ainz Ooal Gown', $aliases);
    }

    /**
     * @test
     */
    public function it_gets_the_about()
    {
        self::assertStringContainsString('He is the guild master of Ainz Ooal Gown,', $this->parser->getAbout());
        self::assertStringContainsString('(Source: Overlord Wikia)', $this->parser->getAbout());
    }

    /**
     * @test
     */
    public function it_gets_the_member_favorites()
    {
        self::assertEquals(12474, $this->parser->getMemberFavorites());
    }

    /**
     * @test
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/characters/3/288006.jpg',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_animeography()
    {
        $animeography = $this->parser->getAnimeography();
        self::assertCount(14, $animeography);
        self::assertContainsOnly(\Jikan\Model\Character\Animeography::class, $animeography);
    }

    /**
     * @test
     */
    public function it_gets_the_mangaography()
    {
        $manaography = $this->parser->getMangaography();
        self::assertCount(2, $manaography);
        self::assertContainsOnly(\Jikan\Model\Character\Mangaography::class, $manaography);
    }

    /**
     * @test
     */
    public function it_gets_the_voice_actors()
    {
        $voiceActors = $this->parser->getVoiceActors();
        self::assertCount(7, $voiceActors);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Character\VoiceActor::class, $voiceActors);
        self::assertEquals('Hino, Satoshi', $voiceActors[0]->getPerson()->getName());
        self::assertEquals('Guerrero, Chris', $voiceActors[1]->getPerson()->getName());
        self::assertEquals('Mendiant, Charles', $voiceActors[3]->getPerson()->getName());
        self::assertEquals('Kaminski, Stefan', $voiceActors[4]->getPerson()->getName());
    }
}
