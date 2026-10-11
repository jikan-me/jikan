<?php

namespace JikanTest\Parser\Common;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Common\DefaultPicturesPageParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class DefaultPicturesPageParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Common\DefaultPicturesPageParser,
 * the parser behind getCharacterPictures() (character/{id}/jikan/pics) and
 * getPersonPictures() (people/{id}/jikan/pics). Character pictures are parsed
 * in setUp; person pictures in their own test.
 */
class DefaultPicturesPageParserTest extends TestCase
{
    /**
     * @var DefaultPicturesPageParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Character\CharacterPicturesRequest(116281);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new DefaultPicturesPageParser($crawler);
    }

    #[Test]
    public function it_parses_character_pictures(): void
    {
        $pictures = $this->parser->getModel();
        self::assertCount(12, $pictures);
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/images/characters/.*~',
            (string) $pictures[0]->getJpg()->getImageUrl()
        );
    }

    #[Test]
    public function it_parses_person_pictures(): void
    {
        $request = new \Jikan\Request\Person\PersonPicturesRequest(118);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $parser = new DefaultPicturesPageParser($crawler);

        $pictures = $parser->getModel();
        self::assertCount(20, $pictures);
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/images/voiceactors/.*~',
            (string) $pictures[0]->getJpg()->getImageUrl()
        );
    }
}