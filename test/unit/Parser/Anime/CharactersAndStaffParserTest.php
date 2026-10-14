<?php

namespace JikanTest\Parser\Anime;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Anime\CharactersAndStaffParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class CharactersAndStaffParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Anime\CharactersAndStaffParser
 * (getAnimeCharactersAndStaff) against the multi-section characters + staff
 * page of Cowboy Bebop (anime 1).
 */
class CharactersAndStaffParserTest extends TestCase
{
    /**
     * @var CharactersAndStaffParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Anime\AnimeCharactersAndStaffRequest(1);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new CharactersAndStaffParser($crawler);
    }

    #[Test]
    public function it_parses_the_character_section(): void
    {
        $characters = $this->parser->getCharacters();
        self::assertCount(126, $characters);

        $first = $characters[0];
        self::assertSame(3, $first->getCharacter()->getMalId());
        self::assertSame('Black, Jet', $first->getCharacter()->getName());
        self::assertSame('Main', $first->getRole());
        self::assertSame(2323, $first->getFavorites());
        self::assertCount(10, $first->getVoiceActors());
    }

    #[Test]
    public function it_parses_the_staff_section(): void
    {
        $staff = $this->parser->getStaff();
        self::assertCount(121, $staff);

        $first = $staff[0];
        self::assertSame(77978, $first->getPerson()->getMalId());
        self::assertSame('Ikeguchi, Kazuhiko', $first->getPerson()->getName());
        self::assertSame(['Producer'], $first->getPositions());
    }

    #[Test]
    public function it_exposes_the_endpoint_model(): void
    {
        $model = $this->parser->getModel();
        self::assertCount(126, $model->getCharacters());
        self::assertCount(121, $model->getStaff());
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
