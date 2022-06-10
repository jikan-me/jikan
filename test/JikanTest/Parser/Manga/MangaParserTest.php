<?php

namespace JikanTest\Parser\Manga;

use Jikan\MyAnimeList\MalClient;
use Jikan\Model\Common\DateRange;
use Jikan\Model\Common\MalUrl;
use JikanTest\TestCase;

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
        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Manga\MangaParser($crawler);

        $jikan = new MalClient($this->httpClient);
        $this->manga = $jikan->getManga(
            $request
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_mal_id()
    {
        self::assertEquals(11, $this->parser->getMangaID());
    }


    /**
     * @test
     */
    public function it_gets_the_manga_url()
    {
        self::assertEquals('https://myanimelist.net/manga/11/Naruto', $this->parser->getMangaURL());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_title()
    {
        self::assertEquals('Naruto', $this->parser->getMangaTitle());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_title_english()
    {
        self::assertEquals('Naruto', $this->parser->getMangaTitleEnglish());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_title_synonyms()
    {
        self::assertEquals([], $this->parser->getMangaTitleSynonyms());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_title_japanese()
    {
        self::assertEquals('NARUTO―ナルト―', $this->parser->getMangaTitleJapanese());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_alternative_titles()
    {
        $titles = $this->parser->getAlternativeTitles();
        self::assertCount(2, $titles);
        self::assertEquals('NARUTO―ナルト―', $titles[0]->getTitle());
        self::assertEquals('Japanese', $titles[0]->getLanguage());
        self::assertEquals('Naruto', $titles[1]->getTitle());
        self::assertEquals('English', $titles[1]->getLanguage());
    }

    /**
     * @test
     */
    public function it_gets_the_manga_image_url()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/manga/3/249658.jpg',
            $this->parser->getMangaImageURL()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_synopsis()
    {
        self::assertEquals(
            "Whenever Naruto Uzumaki proclaims that he will someday become the Hokage—a title bestowed upon the best ninja in the Village Hidden in the Leaves—no one takes him seriously. Since birth, Naruto has been shunned and ridiculed by his fellow villagers. But their contempt isn't because Naruto is loud-mouthed, mischievous, or because of his ineptitude in the ninja arts, but because there is a demon inside him. Prior to Naruto's birth, the powerful and deadly Nine-Tailed Fox attacked the village. In order to stop the rampage, the Fourth Hokage sacrificed his life to seal the demon inside the body of the newborn Naruto. And so when he is assigned to Team 7—along with his new teammates Sasuke Uchiha and Sakura Haruno, under the mentorship of veteran ninja Kakashi Hatake—Naruto is forced to work together with other people for the first time in his life. Through undergoing vigorous training and taking on challenging missions, Naruto must learn what it means to work in a team and carve his own route toward becoming a full-fledged ninja recognized by his village. [Written by MAL Rewrite]",
            $this->parser->getMangaSynopsis()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_type()
    {
        self::assertEquals(
            'Manga',
            $this->parser->getMangaType()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_chapters()
    {
        self::assertEquals(
            700,
            $this->parser->getMangaChapters()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_volumes()
    {
        self::assertEquals(
            72,
            $this->parser->getMangaVolumes()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_status()
    {
        self::assertEquals(
            'Finished',
            $this->parser->getMangaStatus()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_publishing()
    {
        self::assertEquals(
            false,
            $this->manga->isPublishing()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_published()
    {
        $range = $this->manga->getPublished();
        self::assertInstanceOf(DateRange::class, $range);
        self::assertInstanceOf(\DateTimeImmutable::class, $range->getFrom());
        self::assertInstanceOf(\DateTimeImmutable::class, $range->getUntil());
        self::assertEquals('1999-09-21', $range->getFrom()->format('Y-m-d'));
        self::assertEquals('2014-11-10', $range->getUntil()->format('Y-m-d'));
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
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

    /**
     * @test
     */
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


    /**
     * @test
     */
    public function it_gets_the_manga_score()
    {
        self::assertEquals(
            8.06,
            $this->manga->getScore()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_scored_by()
    {
        self::assertEquals(
            250288,
            $this->manga->getScoredBy()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_rank()
    {
        self::assertEquals(
            594,
            $this->manga->getRank()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_popularity()
    {
        self::assertEquals(
            8,
            $this->manga->getPopularity()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_members()
    {
        self::assertEquals(
            377720,
            $this->manga->getMembers()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_favorites()
    {
        self::assertEquals(
            41940,
            $this->manga->getFavorites()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_manga_related()
    {
        $related = $this->parser->getMangaRelated();
        self::assertCount(5, $related);
        self::assertContainsOnlyInstancesOf(MalUrl::class, $related['Alternative version']);
    }

    /**
     * @test
     */
    public function it_gets_the_manga_background()
    {
        $background = $this->manga->getBackground();
        self::assertStringContainsString(
            'Naruto has sold over 220 million copies worldwide as of 2015, making it the 4th highest grossing',
            $background
        );
        self::assertStringContainsString(
            ' Comics/Planet Manga from May 2007 to June 2015, and again as Naruto Gold edition since July 2015.',
            $background
        );
    }

}
