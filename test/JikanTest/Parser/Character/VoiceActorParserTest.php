<?php

namespace JikanTest\Parser\Character;

use JikanTest\TestCase;

/**
 * Class VoiceActorParserTest
 */
class VoiceActorParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Character\VoiceActorParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/character/116281');
        $crawler = $crawler->filterXPath('//div[contains(text(), \'Voice Actors\')]/../table/tr')->first();
        $this->parser = new \Jikan\Parser\Character\VoiceActorParser($crawler);
    }

    /**
     * @test
     */
    public function it_gets_the_mal_id()
    {
        self::assertEquals(245, $this->parser->getMalId());
    }

    /**
     * @test
     */
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/people/245/Satoshi_Hino', $this->parser->getUrl());
    }

    /**
     * @test
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
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/voiceactors/3/65938.jpg',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_language()
    {
        self::assertEquals('Japanese', $this->parser->getLanguage());
    }
}
