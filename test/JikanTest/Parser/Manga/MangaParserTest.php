<?php

namespace JikanTest\Parser\Manga;

use Jikan\Jikan;
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
     * @var \Jikan\Model\Manga
     */
    private $manga;

    public function setUp()
    {
        $request = new \Jikan\Request\Manga(11);
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\Manga\MangaParser($crawler);

        $jikan = new Jikan();
        $this->manga = $jikan->Manga(
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
        self::assertEquals(null, $this->parser->getMangaTitleSynonyms());
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
            'MangaParser',
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
    public function it_gets_the_manga_chapters_unknown()
    {
        self::assertEquals(
            false,
            $this->manga->isChaptersUnknown()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_volumes_unknown()
    {
        self::assertEquals(
            false,
            $this->manga->isVolumesUnknown()
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
    public function it_gets_the_manga_published_string()
    {
        self::assertEquals(
            "Sep  21, 1999 to Nov  10, 2014",
            $this->manga->getPublishedString()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_published()
    {
        self::assertEquals(
            [
                'from' => '1999-09-21',
                'to'   => '2014-11-10',
            ],
            $this->manga->getPublished()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_authors()
    {
        $authors = $this->manga->getAuthors();
        self::assertCount(1, $authors);
        self::assertContainsOnlyInstancesOf(\Jikan\Model\MalUrl::class, $authors);
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
        self::assertContainsOnlyInstancesOf(\Jikan\Model\MalUrl::class, $serializations);
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
        self::assertContainsOnlyInstancesOf(\Jikan\Model\MalUrl::class, $genres);
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
            177621,
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
            707,
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
            258846,
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
            39959,
            $this->manga->getFavorites()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_related()
    {
        $this->markTestSkipped('Todo');
        self::assertEquals(
            [],
            $this->manga->getRelated()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_background()
    {
        self::assertContains(
            'Naruto has sold over 220 million copies worldwide as of 2015, making it the 4th highest grossing',
            $this->manga->getBackground()
        );
        self::assertContains(
            ' Comics/Planet MangaParser from May 2007 to June 2015, and again as Naruto Gold edition since July 2015.',
            $this->manga->getBackground()
        );
    }

}
