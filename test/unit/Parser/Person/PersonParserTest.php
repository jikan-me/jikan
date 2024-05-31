<?php

namespace JikanTest\Parser\Person;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class PersonParserTest
 */
class PersonParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Person\PersonParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Person\PersonRequest(99);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Person\PersonParser($crawler);
    }

    #[Test]
    public function it_gets_the_mal_id()
    {
        self::assertEquals(99, $this->parser->getPersonId());
    }

    #[Test]
    public function it_gets_the_Person_url()
    {
        self::assertEquals('https://myanimelist.net/people/99/Miyuki_Sawashiro', $this->parser->getPersonUrl());
    }

    #[Test]
    public function it_gets_the_name()
    {
        self::assertEquals('Miyuki Sawashiro', $this->parser->getPersonName());
    }

    #[Test]
    public function it_gets_the_given_name()
    {
        self::assertEquals('みゆき', $this->parser->getPersonGivenName());
    }

    #[Test]
    public function it_gets_the_family_name()
    {
        self::assertEquals('沢城', $this->parser->getPersonFamilyName());
    }

    #[Test]
    public function it_gets_the_about()
    {
        self::assertStringContainsString(
            "Hobbies: piano",
            $this->parser->getPersonAbout()
        );
    }

    #[Test]
    public function it_gets_the_member_favorites()
    {
        self::assertEquals(38896, $this->parser->getPersonFavorites());
    }

    #[Test]
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/voiceactors/2/65500.jpg',
            $this->parser->getPersonImageUrl()
        );
    }

    #[Test]
    public function it_gets_the_voice_acting_roles()
    {
        $voiceActingRoles = $this->parser->getPersonVoiceActingRoles();
        self::assertCount(537, $voiceActingRoles);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Person\VoiceActingRole::class, $voiceActingRoles);
    }

    #[Test]
    public function it_gets_the_anime_staff_positions()
    {
        $animeStaffPositions = $this->parser->getPersonAnimeStaffPositions();
        self::assertCount(41, $animeStaffPositions);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Person\AnimeStaffPosition::class, $animeStaffPositions);
    }

    #[Test]
    public function it_gets_the_published_manga()
    {
        $publishedManga = $this->parser->getPersonPublishedManga();
        self::assertCount(0, $publishedManga);
    }
}
