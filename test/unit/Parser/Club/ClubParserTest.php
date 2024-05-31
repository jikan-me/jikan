<?php
namespace JikanTest\Parser\Club;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\UserMetaBasic;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ClubParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Club\ClubParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Club\ClubRequest(1);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Club\ClubParser($crawler);
    }

    #[Test]
    public function it_gets_mal_id(): void
    {
        self::assertEquals(
            1,
            $this->parser->getMalId()
        );
    }

    #[Test]
    public function it_gets_url(): void
    {
        self::assertEquals(
            'https://myanimelist.net/clubs.php?cid=1',
            $this->parser->getUrl()
        );
    }

    #[Test]
    public function it_gets_image_url(): void
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/clubs/16/222057.jpg',
            $this->parser->getImageUrl()
        );
    }

    #[Test]
    public function it_gets_title(): void
    {
        self::assertEquals(
            'Cowboy Bebop',
            $this->parser->getTitle()
        );
    }

    #[Test]
    public function it_gets_members_count(): void
    {
        self::assertEquals(
            1398,
            $this->parser->getMembersCount()
        );
    }

    #[Test]
    public function it_gets_pictures_count(): void
    {
        self::assertEquals(
            25,
            $this->parser->getPicturesCount()
        );
    }

    #[Test]
    public function it_gets_category(): void
    {
        self::assertEquals(
            'anime',
            $this->parser->getCategory()
        );
    }

    #[Test]
    public function it_gets_created(): void
    {
        self::assertEquals(
            1175126400,
            $this->parser->getCreated()->getTimestamp()
        );
    }

    #[Test]
    public function it_gets_staff(): void
    {
        self::assertContainsOnlyInstancesOf(
            UserMetaBasic::class,
            $this->parser->getStaff()
        );
    }

    #[Test]
    public function it_gets_anime_relations(): void
    {
        self::assertContainsOnlyInstancesOf(
            MalUrl::class,
            $this->parser->getAnimeRelations()
        );

        self::assertEquals(
            'Cowboy Bebop',
            $this->parser->getAnimeRelations()[0]->getName()
        );

        self::assertEquals(
            'https://myanimelist.net/anime/1',
            $this->parser->getAnimeRelations()[0]->getUrl()
        );

        self::assertEquals(
            1,
            $this->parser->getAnimeRelations()[0]->getMalId()
        );
    }

    #[Test]
    public function it_gets_manga_relations(): void
    {
        self::assertContainsOnlyInstancesOf(
            MalUrl::class,
            $this->parser->getMangaRelations()
        );

        self::assertEquals(
            'Cowboy Bebop',
            $this->parser->getMangaRelations()[0]->getName()
        );

        self::assertEquals(
            'https://myanimelist.net/manga/173',
            $this->parser->getMangaRelations()[0]->getUrl()
        );

        self::assertEquals(
            173,
            $this->parser->getMangaRelations()[0]->getMalId()
        );
    }

    #[Test]
    public function it_gets_character_relations(): void
    {
        self::assertContainsOnlyInstancesOf(
            MalUrl::class,
            $this->parser->getCharacterRelations()
        );
    }

}
