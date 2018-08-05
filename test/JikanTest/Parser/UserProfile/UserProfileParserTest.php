<?php

namespace JikanTest\Parser\User\Profile;

use PHPUnit\Framework\TestCase;

/**
 * Class ProfileParserTest
 */
class UserProfileParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\User\Profile\UserProfileParser
     */
    private $parser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/profile/sandshark');
        $this->parser = new \Jikan\Parser\User\Profile\UserProfileParser($crawler);
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
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser->getJoinDate());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_last_online()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser->getLastOnline());
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
        self::assertInstanceOf(\Jikan\Model\User\AnimeStats::class, $this->parser->getAnimeStats());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_manga_stats()
    {
        self::assertInstanceOf(\Jikan\Model\User\MangaStats::class, $this->parser->getMangaStats());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_favorites()
    {
        self::assertInstanceOf(\Jikan\Model\User\Favorites::class, $this->parser->getFavorites());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\AnimeMeta::class, $this->parser->getFavorites()->getAnime());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MangaMeta::class, $this->parser->getFavorites()->getManga());
        self::assertContainsOnlyInstancesOf(
            \Jikan\Model\Common\CharacterMeta::class,
            $this->parser->getFavorites()->getCharacters()
        );
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\PersonMeta::class, $this->parser->getFavorites()->getPeople());

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
