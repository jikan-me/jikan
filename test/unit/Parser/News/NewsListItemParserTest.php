<?php

namespace JikanTest\Parser\News;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\News\NewsListItemParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class NewsListItemParserTest
 */
class NewsListItemParserTest extends TestCase
{
    /**
     * @var NewsListItemParser
     */
    private NewsListItemParser $parser;

    public function setUp(): void
    {
        $this->markTestSkipped(
            'Implementation incomplete',
        );

        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/manga/2/Berserk/news');
        $this->parser = new NewsListItemParser(
            $crawler->filterXPath('//div[contains(@class,"js-scrollfix-bottom-rel")]/div[@class="clearfix"]')->first()
        );
    }

    #[Test]
    public function it_gets_the_title(): void
    {
        self::assertEquals("Kentarou Miura's Assistants Resume 'Berserk' Serialization, 'Holyland' Creator Kouji Mori to Supervise", $this->parser->getTitle());
    }

    #[Test]
    public function it_gets_the_url(): void
    {
        self::assertEquals('https://myanimelist.net/news/66547854', $this->parser->getUrl());
    }

    #[Test]
    public function it_gets_the_image(): void
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/s/common/uploaded_files/1654587114-bf53f8de5beebd981afec1932486e604.jpeg?s=14bd951b901aa46034b344818e7cbd31',
            $this->parser->getImageUrl()
        );
    }

    #[Test]
    public function it_gets_the_date(): void
    {
        self::assertEquals('2024-06-07 00:33', $this->parser->getDate()->format('Y-m-d H:i'));
    }

    #[Test]
    public function it_gets_the_author(): void
    {
        self::assertEquals('Vindstot', (string)$this->parser->getAuthor());
    }

    #[Test]
    public function it_gets_the_discussion_link(): void
    {
        self::assertEquals('https://myanimelist.net/forum/?topicid=2021160', $this->parser->getDiscussionLink());
    }

    #[Test]
    public function it_gets_the_comments(): void
    {
        self::assertEquals(
            70,
            $this->parser->getComments()
        );
    }

    #[Test]
    public function it_gets_the_introduction(): void
    {
        self::assertStringContainsString(
            "The editorial department of Young Animal announced on Tuesday that the late Kentarou Miura's Berserk manga will resume serialization",
            $this->parser->getExcerpt()
        );
    }
}
