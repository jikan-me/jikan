<?php

namespace JikanTest\Parser\Character;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Character\CharacterParser($crawler);
    }

    #[Test]
    public function it_gets_the_mal_id()
    {
        self::assertEquals(116281, $this->parser->getMalId());
    }

    #[Test]
    public function it_gets_the_character_url()
    {
        self::assertEquals('https://myanimelist.net/character/116281/Momonga', $this->parser->getCharacterUrl());
    }

    #[Test]
    public function it_gets_the_name()
    {
        self::assertEquals('Momonga', $this->parser->getName());
    }

    #[Test]
    public function it_gets_the_name_in_kanji()
    {
        self::assertEquals('モモンガ', $this->parser->getNameKanji());
    }

    #[Test]
    public function it_gets_the_nicknames()
    {
        $aliases = $this->parser->getNameNicknames();
        self::assertCount(2, $aliases);
        self::assertContains('Momon', $aliases);
        self::assertContains('Ainz Ooal Gown', $aliases);
    }

    #[Test]
    public function it_gets_the_about()
    {
        self::assertStringContainsString('He is the guild master of Ainz Ooal Gown,', $this->parser->getAbout());
        self::assertStringContainsString('(Source: Overlord Wikia)', $this->parser->getAbout());
    }

    #[Test]
    public function it_gets_the_member_favorites()
    {
        self::assertIsNumeric($this->parser->getMemberFavorites());
    }

    #[Test]
    public function it_gets_the_image()
    {
        self::assertMatchesRegularExpression(
            '~https://cdn.myanimelist.net/(.*).jpg~',
            $this->parser->getImage()
        );
    }

    #[Test]
    public function it_gets_the_animeography()
    {
        $animeography = $this->parser->getAnimeography();
        self::assertNotEmpty($animeography);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Character\Animeography::class, $animeography);
    }

    #[Test]
    public function it_gets_the_mangaography()
    {
        $manaography = $this->parser->getMangaography();
        self::assertCount(6, $manaography);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Character\Mangaography::class, $manaography);
    }

    #[Test]
    public function it_gets_the_voice_actors()
    {
        $voiceActors = $this->parser->getVoiceActors();
        self::assertCount(10, $voiceActors);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Character\VoiceActor::class, $voiceActors);
        self::assertEquals('Hino, Satoshi', $voiceActors[0]->getPerson()->getName());
        self::assertEquals('Guerrero, Chris', $voiceActors[1]->getPerson()->getName());
        self::assertEquals('Kaminski, Stefan', $voiceActors[3]->getPerson()->getName());
    }
}
