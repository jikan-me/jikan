<?php /** @noinspection ALL */

/** @noinspection PhpCSValidationInspection */

namespace JikanTest\Parser\Forum;

use Jikan\Model\Forum\ForumPost;
use Jikan\Parser\Forum\ForumTopicParser;
use JikanTest\TestCase;

/**
 * Class ForumTopicParserTest
 */
class ForumTopicParserTest extends TestCase
{
    /**
     * @var ForumTopicParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/1/_/forum');
        $this->parser = new ForumTopicParser($crawler->filterXPath('//tr[contains(@id, "topicRow")]')->eq(2));
    }

    /**
     * @test
     */
    public function it_gets_the_post_id(): void
    {
        self::assertEquals(24885, $this->parser->getTopicId());
    }

    /**
     * @test
     */
    public function it_gets_the_post_url(): void
    {
        self::assertEquals('https://myanimelist.net/forum/?topicid=24885', $this->parser->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_post_title(): void
    {
        self::assertEquals('Cowboy Bebop Episode 18 Discussion', $this->parser->getTitle());
    }

    /**
     * @test
     */
    public function it_gets_the_post_date(): void
    {
        self::assertEquals('2008-05-14', $this->parser->getPostDate()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function it_gets_the_author_name(): void
    {
        self::assertEquals('FighterZ', $this->parser->getAuthorName());
    }

    /**
     * @test
     */
    public function it_gets_the_author_url(): void
    {
        self::assertEquals('https://myanimelist.net/profile/FighterZ', $this->parser->getAuthorUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_replies(): void
    {
        self::assertEquals(160, $this->parser->getReplies());
    }

    /**
     * @test
     */
    public function it_gets_the_last_post(): void
    {
        $lastPost = $this->parser->getLastPost();
        self::assertInstanceOf(ForumPost::class, $lastPost);
        self::assertEquals('Daiko', $lastPost->getAuthorUsername());
        self::assertEquals('https://myanimelist.net/profile/Daiko', $lastPost->getAuthorUrl());
        self::assertEquals('https://myanimelist.net/forum/?topicid=24885&goto=lastpost', $lastPost->getUrl());
        // Last post is 'by  Today, 6:29 AM, so just check hour, not day
        self::assertEquals('17:21', $lastPost->getDate()->format('H:i'));
    }
}
