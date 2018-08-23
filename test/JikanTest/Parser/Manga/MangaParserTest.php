<?php

namespace JikanTest\Parser\Manga;

use Jikan\MyAnimeList\MalClient;
use Jikan\Model\Common\DateRange;
use Jikan\Model\Common\MalUrl;
use PHPUnit\Framework\TestCase;

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

    public function setUp()
    {
        $request = new \Jikan\Request\Manga\MangaRequest(11);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Manga\MangaParser($crawler);

        $jikan = new MalClient;
        $this->manga = $jikan->getManga(
            $request
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_mal_id()
    {
        self::assertEquals(11, $this->parser->getMangaID());
    }


    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_url()
    {
        self::assertEquals('https://myanimelist.net/manga/11/Naruto', $this->parser->getMangaURL());
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_title()
    {
        self::assertEquals('Naruto', $this->parser->getMangaTitle());
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_title_english()
    {
        self::assertEquals('Naruto', $this->parser->getMangaTitleEnglish());
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_title_synonyms()
    {
        self::assertEquals([], $this->parser->getMangaTitleSynonyms());
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_title_japanese()
    {
        self::assertEquals('NARUTO―ナルト―', $this->parser->getMangaTitleJapanese());
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_image_url()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/manga/3/117681.jpg',
            $this->parser->getMangaImageURL()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
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
     * @vcr MangaParserTest.yaml
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
     * @vcr MangaParserTest.yaml
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
     * @vcr MangaParserTest.yaml
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
     * @vcr MangaParserTest.yaml
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
     * @vcr MangaParserTest.yaml
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
     * @vcr MangaParserTest.yaml
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
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_authors()
    {
        $authors = $this->manga->getAuthors();
        self::assertCount(1, $authors);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $authors);
        self::assertContains('Kishimoto, Masashi', $authors);
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_serialization()
    {
        $serializations = $this->manga->getSerializations();
        self::assertCount(1, $serializations);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $serializations);
        self::assertContains('Shounen Jump (Weekly)', $serializations);
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_genre()
    {
        $genres = $this->manga->getGenres();
        self::assertCount(5, $genres);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\Common\MalUrl::class, $genres);
        self::assertContains('Action', $genres);
        self::assertContains('Adventure', $genres);
        self::assertContains('Martial Arts', $genres);
        self::assertContains('Shounen', $genres);
        self::assertContains('Super Power', $genres);
    }


    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_score()
    {
        self::assertEquals(
            8.11,
            $this->manga->getScore()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_scored_by()
    {
        self::assertEquals(
            177655,
            $this->manga->getScoredBy()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_rank()
    {
        self::assertEquals(
            706,
            $this->manga->getRank()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_popularity()
    {
        self::assertEquals(
            1,
            $this->manga->getPopularity()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_members()
    {
        self::assertEquals(
            258896,
            $this->manga->getMembers()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_favorites()
    {
        self::assertEquals(
            39966,
            $this->manga->getFavorites()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_related()
    {
        $related = $this->parser->getMangaRelated();
        self::assertCount(5, $related);
        self::assertContainsOnlyInstancesOf(MalUrl::class, $related['Alternative version']);
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_background()
    {
        $background = $this->manga->getBackground();
        self::assertContains(
            'Naruto has sold over 220 million copies worldwide as of 2015, making it the 4th highest grossing',
            $background
        );
        self::assertContains(
            ' Comics/Planet Manga from May 2007 to June 2015, and again as Naruto Gold edition since July 2015.',
            $background
        );
    }

}
