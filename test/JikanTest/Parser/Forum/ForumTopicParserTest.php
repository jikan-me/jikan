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

    public function setUp()
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/21/_/forum');
        $this->parser = new ForumTopicParser($crawler->filterXPath('//tr[contains(@id, "topicRow")]')->eq(2));
    }

    /**
     * @test
     * @vcr ForumTopicParserTest.yaml
     */
    public function it_gets_the_post_id(): void
    {
        self::assertEquals(57389, $this->parser->getTopicId());
    }

    /**
     * @test
     * @vcr ForumTopicParserTest.yaml
     */
    public function it_gets_the_post_url(): void
    {
        self::assertEquals('https://myanimelist.net/forum/?topicid=57389', $this->parser->getUrl());
    }

    /**
     * @test
     * @vcr ForumTopicParserTest.yaml
     */
    public function it_gets_the_post_title(): void
    {
        self::assertEquals('One Piece Episode 381 Discussion', $this->parser->getTitle());
    }

    /**
     * @test
     * @vcr ForumTopicParserTest.yaml
     */
    public function it_gets_the_post_date(): void
    {
        self::assertEquals('2008-12-14', $this->parser->getPostDate()->format('Y-m-d'));
    }

    /**
     * @test
     * @vcr ForumTopicParserTest.yaml
     */
    public function it_gets_the_author_name(): void
    {
        self::assertEquals('VK11', $this->parser->getAuthorName());
    }

    /**
     * @test
     * @vcr ForumTopicParserTest.yaml
     */
    public function it_gets_the_author_url(): void
    {
        self::assertEquals('https://myanimelist.net/profile/VK11', $this->parser->getAuthorUrl());
    }

    /**
     * @test
     * @vcr ForumTopicParserTest.yaml
     */
    public function it_gets_the_replies(): void
    {
        self::assertEquals(69, $this->parser->getReplies());
    }

    /**
     * @test
     * @vcr ForumTopicParserTest.yaml
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
