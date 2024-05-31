<?php

namespace JikanTest\Parser\Manga;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Common\DateRange;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\Title;
use Jikan\MyAnimeList\MalClient;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class MangaParserTest
 */
class MangaParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Manga\MangaParser
     */
    private $parser;

    /**
     * @var \Jikan\Model\Manga\Manga
     */
    private $manga;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Manga\MangaRequest(11);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Manga\MangaParser($crawler);

        $jikan = new MalClient($this->httpClient);
        $this->manga = $jikan->getManga(
            $request
        );
    }

    #[Test]
    public function it_gets_the_manga_mal_id()
    {
        self::assertEquals(11, $this->parser->getMangaID());
    }


    #[Test]
    public function it_gets_the_manga_url()
    {
        self::assertEquals('https://myanimelist.net/manga/11/Naruto', $this->parser->getMangaURL());
    }

    #[Test]
    public function it_gets_the_manga_title()
    {
        self::assertEquals('Naruto', $this->parser->getMangaTitle());
    }

    #[Test]
    public function it_gets_the_manga_title_english()
    {
        self::assertEquals('Naruto', $this->parser->getMangaTitleEnglish());
    }

    #[Test]
    public function it_gets_the_manga_title_synonyms()
    {
        self::assertEquals([], $this->parser->getMangaTitleSynonyms());
    }

    #[Test]
    public function it_gets_the_manga_title_japanese()
    {
        self::assertEquals('NARUTO―ナルト―', $this->parser->getMangaTitleJapanese());
    }

    #[Test]
    public function it_gets_the_manga_titles()
    {
        $titles = $this->parser->getTitles();
        self::assertCount(3, $titles);
        self::assertEquals(new Title('Default', 'Naruto'), $titles[0]);
        self::assertEquals(new Title('Japanese', 'NARUTO―ナルト―'), $titles[1]);
        self::assertEquals(new Title('English', 'Naruto'), $titles[2]);
    }

    #[Test]
    public function it_gets_the_manga_image_url()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/manga/3/249658.jpg',
            $this->parser->getMangaImageURL()
        );
    }

    #[Test]
    public function it_gets_the_manga_synopsis()
    {
        self::assertStringContainsString(
            "Whenever Naruto Uzumaki proclaims that he will someday become the Hokage—a title bestowed upon the best ninja in the Village Hidden",
            $this->parser->getMangaSynopsis()
        );
    }

    #[Test]
    public function it_gets_the_manga_type()
    {
        self::assertEquals(
            'Manga',
            $this->parser->getMangaType()
        );
    }

    #[Test]
    public function it_gets_the_manga_chapters()
    {
        self::assertEquals(
            700,
            $this->parser->getMangaChapters()
        );
    }

    #[Test]
    public function it_gets_the_manga_volumes()
    {
        self::assertEquals(
            72,
            $this->parser->getMangaVolumes()
        );
    }

    #[Test]
    public function it_gets_the_manga_status()
    {
        self::assertEquals(
            'Finished',
            $this->parser->getMangaStatus()
        );
    }

    #[Test]
    public function it_gets_the_manga_publishing()
    {
        self::assertEquals(
            false,
            $this->manga->isPublishing()
        );
    }

    #[Test]
    public function it_gets_the_manga_published()
    {
        $range = $this->manga->getPublished();
        self::assertInstanceOf(DateRange::class, $range);
        self::assertInstanceOf(\DateTimeImmutable::class, $range->getFrom());
        self::assertInstanceOf(\DateTimeImmutable::class, $range->getUntil());
        self::assertEquals('1999-09-21', $range->getFrom()->format('Y-m-d'));
        self::assertEquals('2014-11-10', $range->getUntil()->format('Y-m-d'));
    }

    #[Test]
    public function it_gets_the_manga_authors()
    {
        $authors = $this->manga->getAuthors();
        self::assertCount(1, $authors);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $authors);
        $names = array_map(function ($item) {
            return $item->getName();
        }, $authors);
        self::assertContains('Kishimoto, Masashi', $names);
    }

    #[Test]
    public function it_gets_the_manga_serialization()
    {
        $serializations = $this->manga->getSerializations();
        self::assertCount(1, $serializations);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $serializations);
        $names = array_map(function ($item) {
            return $item->getName();
        }, $serializations);
        self::assertContains('Shounen Jump (Weekly)', $names);
    }

    #[Test]
    public function it_gets_the_manga_genre()
    {
        $genres = $this->manga->getGenres();
        self::assertCount(3, $genres);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $genres);
        $names = array_map(function ($item) {
            return $item->getName();
        }, $genres);
        self::assertContains('Action', $names);
        self::assertContains('Adventure', $names);
        self::assertContains('Fantasy', $names);
    }


    #[Test]
    public function it_gets_the_manga_score()
    {
        self::assertEquals(
            8.07,
            $this->manga->getScore()
        );
    }

    #[Test]
    public function it_gets_the_manga_scored_by()
    {
        self::assertEquals(
            254718,
            $this->manga->getScoredBy()
        );
    }

    #[Test]
    public function it_gets_the_manga_rank()
    {
        self::assertEquals(
            577,
            $this->manga->getRank()
        );
    }

    #[Test]
    public function it_gets_the_manga_popularity()
    {
        self::assertEquals(
            8,
            $this->manga->getPopularity()
        );
    }

    #[Test]
    public function it_gets_the_manga_members()
    {
        self::assertEquals(
            385378,
            $this->manga->getMembers()
        );
    }

    #[Test]
    public function it_gets_the_manga_favorites()
    {
        self::assertEquals(
            42312,
            $this->manga->getFavorites()
        );
    }

    #[Test]
    public function it_gets_the_manga_related()
    {
        $related = $this->parser->getMangaRelated();
        self::assertCount(5, $related);
        self::assertContainsOnlyInstancesOf(MalUrl::class, $related['Alternative version']);
    }

    #[Test]
    public function it_gets_the_manga_background()
    {
        $background = $this->manga->getBackground();
        self::assertStringContainsString(
            'Naruto has sold over 250 million copies worldwide as of 2020, making it the 4th highest grossing manga series of all time.',
            $background
        );
        self::assertStringContainsString(
            'The series was published in English by VIZ Media under the Shonen Jump',
            $background
        );
    }

}
