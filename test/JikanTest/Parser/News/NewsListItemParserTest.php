<?php

namespace JikanTest\Parser\Anime;

use Jikan\Parser\News\NewsListItemParser;
use PHPUnit\Framework\TestCase;

/**
 * Class NewsListItemParserTest
 */
class NewsListItemParserTest extends TestCase
{
    /**
     * @var NewsListItemParser
     */
    private $parser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/2/Berserk/news');
        $this->parser = new NewsListItemParser(
            $crawler->filterXPath('//div[@class="js-scrollfix-bottom-rel"]/div[@class="clearfix"]')->first()
        );
    }

    /**
     * @test
     * @vcr NewsParserTest.yaml
     */
    public function it_gets_the_title(): void
    {
        self::assertEquals('Manga \'Berserk\' Resumes Serialization', $this->parser->getTitle());
    }

    /**
     * @test
     * @vcr NewsParserTest.yaml
     */
    public function it_gets_the_url(): void
    {
        self::assertEquals('https://myanimelist.net/news/53304997', $this->parser->getUrl());
    }

    /**
     * @test
     * @vcr NewsParserTest.yaml
     */
    public function it_gets_the_image(): void
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/s/common/uploaded_files/1512787795-f1674d6456f90126448afb689c3224be.jpeg?s=66f1c0637fa3b5b7e90dc0f40f608738',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     * @vcr NewsParserTest.yaml
     */
    public function it_gets_the_date(): void
    {
        self::assertEquals('2017-12-08 18:53', $this->parser->getDate()->format('Y-m-d H:i'));
    }

    /**
     * @test
     * @vcr NewsParserTest.yaml
     */
    public function it_gets_the_author(): void
    {
        self::assertEquals('Vindstot', (string)$this->parser->getAuthor());
    }

    /**
     * @test
     * @vcr NewsParserTest.yaml
     */
    public function it_gets_the_discussion_link(): void
    {
        self::assertEquals('https://myanimelist.net/forum/?topicid=1690998', $this->parser->getDiscussionLink());
    }

    /**
     * @test
     * @vcr NewsParserTest.yaml
     */
    public function it_gets_the_comments(): void
    {
        self::assertEquals(
            31,
            $this->parser->getComments()
        );
    }

    /**
     * @test
     * @vcr NewsParserTest.yaml
     */
    public function it_gets_the_introduction(): void
    {
        self::assertEquals(
            'The 24th issue of this year\'s Young Animal magazine has announced on Friday that Kentarou Miura\'s adventure fantasy manga Berserk will resume its serializa...',
            $this->parser->getIntro()
        );
    }
}
