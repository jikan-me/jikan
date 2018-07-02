<?php

namespace JikanTest\Parser\UserProfile;

use PHPUnit\Framework\TestCase;

/**
 * Class ProfileParserTest
 */
class UserProfileParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\UserProfile\UserProfileParser
     */
    private $parser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/profile/sandshark');
        $this->parser = new \Jikan\Parser\UserProfile\UserProfileParser($crawler);
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_username()
    {
        self::assertEquals('sandshark', $this->parser->getUsername());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/profile/sandshark', $this->parser->getProfileUrl());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/userimages/3600201.jpg',
            $this->parser->getImageUrl()
        );
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_join_date()
    {
        self::assertEquals('Feb 26, 2014', $this->parser->getJoinDate());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_last_online()
    {
        self::assertEquals('Yesterday, 6:37 AM', $this->parser->getLastOnline());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_gender()
    {
        self::assertEquals('Male', $this->parser->getGender());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_birthday()
    {
        self::assertEquals(null, $this->parser->getBirthday());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_location()
    {
        self::assertEquals('101', $this->parser->getLocation());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_anime_stats()
    {
        self::assertInstanceOf(\Jikan\Model\AnimeStats::class, $this->parser->getAnimeStats());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_manga_stats()
    {
        self::assertInstanceOf(\Jikan\Model\MangaStats::class, $this->parser->getMangaStats());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_favorites()
    {
        self::assertInstanceOf(\Jikan\Model\Favorites::class, $this->parser->getFavorites());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\AnimeMeta::class, $this->parser->getFavorites()->getAnime());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\MangaMeta::class, $this->parser->getFavorites()->getManga());
        self::assertContainsOnlyInstancesOf(
            \Jikan\Model\CharacterMeta::class,
            $this->parser->getFavorites()->getCharacters()
        );
        self::assertContainsOnlyInstancesOf(\Jikan\Model\PersonMeta::class, $this->parser->getFavorites()->getPeople());

    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_about()
    {
        self::assertEquals(null, $this->parser->getAbout());
    }
}
