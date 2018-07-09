<?php

namespace JikanTest\Parser\Character;

use PHPUnit\Framework\TestCase;

/**
 * Class VoiceActorParserTest
 */
class VoiceActorParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Character\VoiceActorParser
     */
    private $parser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/character/116281');
        $crawler = $crawler->filterXPath('//div[contains(text(), \'Voice Actors\')]/../table/tr')->first();
        $this->parser = new \Jikan\Parser\Character\VoiceActorParser($crawler);
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_mal_id()
    {
        self::assertEquals(245, $this->parser->getMalId());
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/people/245/Satoshi_Hino', $this->parser->getUrl());
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_person()
    {
        $person = $this->parser->getPerson();
        self::assertInstanceOf(\Jikan\Model\Common\MalUrl::class, $person);
        self::assertEquals('Hino, Satoshi', $person);
        self::assertEquals('https://myanimelist.net/people/245/Satoshi_Hino', $person->getUrl());
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/voiceactors/3/18359v.jpg',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     * @vcr CharacterParserTest.yaml
     */
    public function it_gets_the_language()
    {
        self::assertEquals('Japanese', $this->parser->getLanguage());
    }
}
