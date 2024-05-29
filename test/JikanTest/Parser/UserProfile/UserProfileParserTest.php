<?php

namespace JikanTest\Parser\User\Profile;

use Jikan\Parser\User\Profile\UserProfileParser;
use JikanTest\TestCase;

/**
 * Class ProfileParserTest
 */
class UserProfileParserTest extends TestCase
{
    /**
     * @var UserProfileParser
     */
    private UserProfileParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/profile/sandshark');
        $this->parser = new UserProfileParser($crawler);
    }

    /**
     * @test
     */
    public function it_gets_the_username()
    {
        self::assertEquals('sandshark', $this->parser->getUsername());
    }

    /**
     * @test
     */
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/profile/sandshark', $this->parser->getProfileUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/userimages/3600201.jpg?t=1664231400',
            $this->parser->getImageUrl()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_join_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser->getJoinDate());
    }

    /**
     * @test
     */
    public function it_gets_the_last_online()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser->getLastOnline());
    }

    /**
     * @test
     */
    public function it_gets_the_gender()
    {
        self::assertEquals('Male', $this->parser->getGender());
    }

    /**
     * @test
     */
    public function it_gets_the_birthday()
    {
        self::assertEquals(null, $this->parser->getBirthday());
    }

    /**
     * @test
     */
    public function it_gets_the_location()
    {
        self::assertEquals('The wired', $this->parser->getLocation());
    }

    /**
     * @test
     */
    public function it_gets_the_anime_stats()
    {
        self::assertInstanceOf(\Jikan\Model\User\AnimeStats::class, $this->parser->getAnimeStats());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_stats()
    {
        self::assertInstanceOf(\Jikan\Model\User\MangaStats::class, $this->parser->getMangaStats());
    }

    /**
     * @test
     */
    public function it_gets_the_favorites()
    {
        self::assertInstanceOf(\Jikan\Model\User\Favorites::class, $this->parser->getFavorites());
        self::assertContainsOnlyInstancesOf(
            \Jikan\Model\User\FavoriteAnime::class,
            $this->parser->getFavorites()->getAnime()
        );
        self::assertContainsOnlyInstancesOf(
            \Jikan\Model\User\FavoriteManga::class,
            $this->parser->getFavorites()->getManga()
        );
        self::assertContainsOnlyInstancesOf(
            \Jikan\Model\Common\CharacterMeta::class,
            $this->parser->getFavorites()->getCharacters()
        );
        self::assertContainsOnlyInstancesOf(
            \Jikan\Model\Common\PersonMeta::class,
            $this->parser->getFavorites()->getPeople()
        );
    }

    /**
     * @test
     */
    public function it_gets_last_updates(){
        $updates = $this->parser->getUserLastUpdates();
        self::assertContainsOnlyInstancesOf(\Jikan\Model\User\LastAnimeUpdate::class, $updates->getAnime());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\User\LastMangaUpdate::class, $updates->getManga());
    }

    /**
     * @test
     */
    public function it_gets_the_about()
    {
        self::assertEquals(null, $this->parser->getAbout());
    }
}
