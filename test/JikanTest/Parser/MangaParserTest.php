<?php

use PHPUnit\Framework\TestCase;
use Jikan\Jikan;

/**
 * Class MangaParserTest
 */
class MangaParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Manga
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
        $this->parser = new \Jikan\Parser\Manga($crawler);

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
                'to' => '2014-11-10'
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
        $this->markTestSkipped('need to review');
        self::assertEquals(
            [
                [
                    'url' => 'https://mymangalist.net/manga/producer/169/Fuji_TV',
                    'name' => 'Fuji TV'
                ],
                [
                    'url' => 'https://mymangalist.net/manga/producer/416/TAP',
                    'name' => 'TAP'
                ],
                [
                    'url' => 'https://mymangalist.net/manga/producer/1365/Shueisha',
                    'name' => 'Shueisha'
                ],
            ],
            $this->manga->getAuthors()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_serialization()
    {
        $this->markTestSkipped('need to review');
        self::assertEquals(
            [
                [
                    'url' => 'https://mymangalist.net/manga/producer/102/Funimation',
                    'name' => 'Funimation'
                ],
                [
                    'url' => 'https://mymangalist.net/manga/producer/252/4Kids_Entertainment',
                    'name' => '4Kids Entertainment'
                ]
            ],
            $this->manga->getSerialization()
        );
    }

    /**
     * @test
     * @vcr MangaParserTest.yaml
     */
    public function it_gets_the_manga_genre()
    {
        $this->markTestSkipped('need to review');
        self::assertEquals(
            [
                [
                    'url' => 'https://mymangalist.net/manga/genre/1/Action',
                    'name' => 'Action'
                ],
                [
                    'url' => 'https://mymangalist.net/manga/genre/2/Adventure',
                    'name' => 'Adventure'
                ],
                [
                    'url' => 'https://mymangalist.net/manga/genre/4/Comedy',
                    'name' => 'Comedy'
                ],
                [
                    'url' => 'https://mymangalist.net/manga/genre/31/Super_Power',
                    'name' => 'Super Power'
                ],
                [
                    'url' => 'https://mymangalist.net/manga/genre/8/Drama',
                    'name' => 'Drama'
                ],
                [
                    'url' => 'https://mymangalist.net/manga/genre/10/Fantasy',
                    'name' => 'Fantasy'
                ],
                [
                    'url' => 'https://mymangalist.net/manga/genre/27/Shounen',
                    'name' => 'Shounen'
                ],

            ],
            $this->manga->getGenre()
        );
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
        $this->markTestSkipped('Review');
        self::assertEquals(
            "Naruto has sold over 220 million copies worldwide as of 2015, making it the 4th highest grossing manga series of all time. It was nominated for the 19th Tezuka Osamu Cultural Prize in 2014, and in the same year Masashi Kishimoto was awarded Rookie of the Year in the media fine arts category by Japan's Agency for Cultural Affairs.\n\n Numerous databooks, artbooks, novels, and fanbooks on the series have been released. Eight summary volumes featuring unaltered color pages, larger dimensions, and exclusive interviews, covering the first part of the series were released between November 7, 2008 and April 10, 2009.\n\n The series was published in English by VIZ Media under the Shonen Jump imprint from August 16, 2003 to October 6, 2015. In the last four months of 2007, the campaign titled \"Naruto Nation\" was launched, in which three volumes were published each month so that US releases would be closer to Japan's, the same practice was done in February through April of 2009 this time titled \"Generation Ninja.\" A 3-in-1 omnibus edition has also been released since May 3, 2011. A box set containing volumes 1-27 was released on August 6, 2008, another one containing volumes 28-48 on July 7, 2015, and the final box set with volumes 49-72 on January 5, 2016. It was also published in Brazilian Portuguese by Panini Comics/Planet Manga from May 2007 to June 2015, and again as Naruto Gold edition since July 2015.",
            $this->manga->getBackground()
        );
    }

}
