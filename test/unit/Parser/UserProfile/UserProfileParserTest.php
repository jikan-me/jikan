<?php

namespace JikanTest\Parser\UserProfile;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\User\Profile\UserProfileParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

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

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/profile/sandshark');
        $this->parser = new UserProfileParser($crawler);
    }

    #[Test]
    public function it_gets_the_username()
    {
        self::assertEquals('sandshark', $this->parser->getUsername());
    }

    #[Test]
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/profile/sandshark', $this->parser->getProfileUrl());
    }

    #[Test]
    public function it_gets_the_image()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/s/common/userimages/7168664d-c9f3-4e6c-9936-bf7b3cb25e6b_225w?s=6a5507ec62b4f11918233a31f37a0490',
            $this->parser->getImageUrl()
        );
    }

    #[Test]
    public function it_gets_the_join_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser->getJoinDate());
    }

    #[Test]
    public function it_gets_the_last_online()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser->getLastOnline());
    }

    #[Test]
    public function it_gets_the_gender()
    {
        self::assertEquals('Male', $this->parser->getGender());
    }

    #[Test]
    public function it_gets_the_birthday()
    {
        self::assertEquals(null, $this->parser->getBirthday());
    }

    #[Test]
    public function it_gets_the_location()
    {
        self::assertEquals('The wired', $this->parser->getLocation());
    }

    #[Test]
    public function it_gets_the_anime_stats()
    {
        self::assertInstanceOf(\Jikan\Model\User\AnimeStats::class, $this->parser->getAnimeStats());
    }

    #[Test]
    public function it_gets_the_manga_stats()
    {
        self::assertInstanceOf(\Jikan\Model\User\MangaStats::class, $this->parser->getMangaStats());
    }

    #[Test]
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

    #[Test]
    public function it_gets_last_updates(){
        $updates = $this->parser->getUserLastUpdates();
        self::assertContainsOnlyInstancesOf(\Jikan\Model\User\LastAnimeUpdate::class, $updates->getAnime());
        self::assertContainsOnlyInstancesOf(\Jikan\Model\User\LastMangaUpdate::class, $updates->getManga());
    }

    #[Test]
    public function it_gets_the_about()
    {
        self::assertEquals(null, $this->parser->getAbout());
    }
}
