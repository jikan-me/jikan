<?php

namespace JikanTest\Parser\Manga;

use Jikan\Parser\Manga\CharactersParser;
use PHPUnit\Framework\TestCase;

/**
 * Class CharactersParserTest
 */
class CharactersParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Manga\CharactersParser
     */
    private $parser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/2/Berserk/characters');
        $this->parser = new CharactersParser($crawler);
    }

    /**
     * @test
     * @vcr MangaCharacterListParserTest.yaml
     */
    public function it_gets_the_manga_characters()
    {
        $characters = $this->parser->getCharacters();
        self::assertCount(70, $characters);
        self::assertContains('Wyald', $characters);
        self::assertContains('Casca', $characters);
        self::assertEquals('Main', $characters[0]->getRole());
    }
}
