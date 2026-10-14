<?php

namespace JikanTest\Parser\Manga;

use JikanTest\ModelSnapshot;
use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Manga\CharactersParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function it_gets_the_manga_characters()
    {
        $characters = $this->parser->getCharacters();
        self::assertCount(81, $characters);
        $names = array_map(function ($item) {
            return $item->getCharacter()->getName();
        }, $characters);
        self::assertContains('Wyald', $names);
        self::assertContains('Casca', $names);
        self::assertEquals('Main', $characters[0]->getRole());
    }

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        self::assertEquals($this->parser->getCharacters(), $this->parser->getCharacters());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser->getCharacters());
    }
}
