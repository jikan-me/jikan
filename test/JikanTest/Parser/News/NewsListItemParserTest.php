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

    public function setUp(): void
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/2/Berserk/news');
        $this->parser = new NewsListItemParser(
            $crawler->filterXPath('//div[@class="js-scrollfix-bottom-rel"]/div[@class="clearfix"]')->first()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_title(): void
    {
        self::assertEquals('North American Anime & Manga Releases for July', $this->parser->getTitle());
    }

    /**
     * @test
     */
    public function it_gets_the_url(): void
    {
        self::assertEquals('https://myanimelist.net/news/60161703', $this->parser->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_image(): void
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/s/common/uploaded_files/1594161493-e76e48dafd1b0f67ece6f1fa065db158.jpeg?s=e24b9ee8cfa8d123bf27fbbd9aef0d27',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_date(): void
    {
        self::assertEquals('2020-07-07 15:39', $this->parser->getDate()->format('Y-m-d H:i'));
    }

    /**
     * @test
     */
    public function it_gets_the_author(): void
    {
        self::assertEquals('ImperfectBlue', (string)$this->parser->getAuthor());
    }

    /**
     * @test
     */
    public function it_gets_the_discussion_link(): void
    {
        self::assertEquals('https://myanimelist.net/forum/?topicid=1850747', $this->parser->getDiscussionLink());
    }

    /**
     * @test
     */
    public function it_gets_the_comments(): void
    {
        self::assertEquals(
            0,
            $this->parser->getComments()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_introduction(): void
    {
        self::assert(
            'Here are the North American anime & manga releases for July Week 1: July 7 - 13 Anime Releases Cop Craft Complete Collection Blu-ray Dumbbell Nan Kilo Moteru? (H...',
            $this->parser->getIntro()
        );
    }
}
