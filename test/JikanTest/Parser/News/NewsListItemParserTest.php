<?php

namespace JikanTest\Parser\Anime;

use Jikan\Parser\News\NewsListItemParser;
use JikanTest\TestCase;

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
        parent::setUp();

        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/2/Berserk/news');
        $this->parser = new NewsListItemParser(
            $crawler->filterXPath('//div[contains(@class,"js-scrollfix-bottom-rel")]/div[@class="clearfix"]')->first()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_title(): void
    {
        self::assertEquals("'Berserk' Creator Kentarou Miura Dies at 54", $this->parser->getTitle());
    }

    /**
     * @test
     */
    public function it_gets_the_url(): void
    {
        self::assertEquals('https://myanimelist.net/news/63203251', $this->parser->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_image(): void
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/s/common/uploaded_files/1621492994-97c345f3d0912b89a1207f235274b1a4.jpeg?s=b42ac2bcc10470f9f29e6d4647f4c4fc',
            $this->parser->getImage()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_date(): void
    {
        self::assertEquals('2021-05-19 21:39', $this->parser->getDate()->format('Y-m-d H:i'));
    }

    /**
     * @test
     */
    public function it_gets_the_author(): void
    {
        self::assertEquals('Vindstot', (string)$this->parser->getAuthor());
    }

    /**
     * @test
     */
    public function it_gets_the_discussion_link(): void
    {
        self::assertEquals('https://myanimelist.net/forum/?topicid=1924224', $this->parser->getDiscussionLink());
    }

    /**
     * @test
     */
    public function it_gets_the_comments(): void
    {
        self::assertEquals(
            437,
            $this->parser->getComments()
        );
    }

    /**
     * @test
     */
    public function it_gets_the_introduction(): void
    {
        self::assertStringContainsString(
            'Prolific manga author Kentarou Miura, best known for creating Berserk, died on May 6 at 2:48 p.m. due to an acute aortic dissection.',
            $this->parser->getIntro()
        );
    }
}
