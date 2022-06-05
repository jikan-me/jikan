<?php /** @noinspection ALL */

/** @noinspection PhpCSValidationInspection */

namespace JikanTest\Parser\Anime;

use Jikan\Model\Forum\ForumPost;
use Jikan\Parser\Forum\ForumTopicParser;
use PHPUnit\Framework\TestCase;

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
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/21/_/forum');
        $this->parser = new ForumTopicParser($crawler->filterXPath('//tr[contains(@id, "topicRow")]')->eq(2));
    }

    /**
     * @test
     */
    public function it_gets_the_post_id(): void
    {
        self::assertEquals(1881620, $this->parser->getTopicId());
    }

    /**
     * @test
     */
    public function it_gets_the_post_url(): void
    {
        self::assertEquals('https://myanimelist.net/forum/?topicid=1881620', $this->parser->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_post_title(): void
    {
        self::assertEquals('One Piece Episode 954 Discussion', $this->parser->getTitle());
    }

    /**
     * @test
     */
    public function it_gets_the_post_date(): void
    {
        self::assertEquals('2020-12-12', $this->parser->getPostDate()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function it_gets_the_author_name(): void
    {
        self::assertEquals('xeonite', $this->parser->getAuthorName());
    }

    /**
     * @test
     */
    public function it_gets_the_author_url(): void
    {
        self::assertEquals('https://myanimelist.net/profile/xeonite', $this->parser->getAuthorUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_replies(): void
    {
        self::assertEquals(39, $this->parser->getReplies());
    }

    /**
     * @test
     */
    public function it_gets_the_last_post(): void
    {
        $lastPost = $this->parser->getLastPost();
        self::assertInstanceOf(ForumPost::class, $lastPost);
        self::assertEquals('gfsdfgsdgsdfgs', $lastPost->getAuthorName());
        self::assertEquals('https://myanimelist.net/profile/gfsdfgsdgsdfgs', $lastPost->getAuthorUrl());
        self::assertEquals('https://myanimelist.net/forum/?topicid=57389&goto=lastpost', $lastPost->getUrl());
        // Last post is 'by  Today, 6:29 AM, so just check hour, not day
        self::assertEquals('06:29', $lastPost->getDatePosted()->format('H:i'));
    }
}
