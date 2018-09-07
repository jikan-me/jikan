<?php

namespace JikanTest\Parser\Character;

use Goutte\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class StaffListItemParserTest
 */
class StaffListItemParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Anime\StaffListItemParser
     */
    private $parser;

    public function setUp()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/35073/_/characters');

        $this->parser = new \Jikan\Parser\Anime\StaffListItemParser(
            $crawler->filterXPath('//h2/div/../following-sibling::table')
                ->eq(4)
        );
    }

    /**
     * @test
     * @vcr CharactersParserTest.yaml
     */
    public function it_gets_the_mal_id()
    {
        self::assertEquals(12596, $this->parser->getMalId());
    }

    /**
     * @test
     * @vcr CharactersParserTest.yaml
     */
    public function it_gets_the_name()
    {
        self::assertEquals('Tom-H@ck', $this->parser->getName());
    }

    /**
     * @test
     * @vcr CharactersParserTest.yaml
     */
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/people/12596/Tom-Hck', $this->parser->getUrl());
    }

    /**
     * @test
     * @vcr CharactersParserTest.yaml
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/voiceactors/3/33089.jpg?s=81a13198b1b0772a7565e8786b94cfe8',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     * @vcr CharactersParserTest.yaml
     */
    public function it_gets_the_positions()
    {
        $positions = $this->parser->getPositions();
        self::assertCount(2, $positions);
        self::assertContains('Theme Song Composition', $positions);
        self::assertContains('Theme Song Arrangement', $positions);
    }
}
