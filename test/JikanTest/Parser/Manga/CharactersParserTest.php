<?php

namespace Parser\Manga;

use Jikan\Parser\Manga\CharactersParser;
use JikanTest\Parser\Manga\HttpClientWrapper;
use TestCase;

/**
 * Class CharactersParserTest
 */
class CharactersParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Manga\CharactersParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/2/Berserk/characters');
        $this->parser = new CharactersParser($crawler);
    }

    /**
     * @test
     */
    public function it_gets_the_manga_characters()
    {
        $characters = $this->parser->getCharacters();
        self::assertCount(75, $characters);
        $names = array_map(function ($item) {
            return $item->getCharacter()->getName();
        }, $characters);
        self::assertContains('Wyald', $names);
        self::assertContains('Casca', $names);
        self::assertEquals('Main', $characters[0]->getRole());
    }
}
