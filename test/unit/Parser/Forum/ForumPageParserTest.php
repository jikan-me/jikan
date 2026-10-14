<?php

namespace JikanTest\Parser\Forum;

use JikanTest\ModelSnapshot;
use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Forum\ForumPageParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ForumPageParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Forum\ForumPageParser, the parser
 * behind getAnimeForum() (anime/{id}/_/forum) and getMangaForum()
 * (manga/{id}/_/forum). The anime forum is parsed in setUp; the manga forum in
 * its own test.
 */
class ForumPageParserTest extends TestCase
{
    /**
     * @var ForumPageParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Anime\AnimeForumRequest(1);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new ForumPageParser($crawler);
    }

    #[Test]
    public function it_parses_the_anime_forum_topics(): void
    {
        self::assertCount(15, $this->parser->getTopics());
    }

    #[Test]
    public function it_parses_the_first_anime_topic(): void
    {
        $topic = $this->parser->getTopics()[0];
        self::assertSame(24838, $topic->getMalId());
        self::assertSame('Cowboy Bebop Episode 26 Discussion', $topic->getTitle());
        self::assertSame(727, $topic->getComments());
        self::assertSame('Metty', $topic->getAuthorUsername());
    }

    #[Test]
    public function it_parses_the_manga_forum_topics(): void
    {
        $request = new \Jikan\Request\Manga\MangaForumRequest(2);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $parser = new ForumPageParser($crawler);

        $topics = $parser->getTopics();
        self::assertCount(15, $topics);
        self::assertSame(110887, $topics[0]->getMalId());
        self::assertSame('Berserk Chapter 2 Discussion', $topics[0]->getTitle());
        self::assertSame(57, $topics[0]->getComments());
    }

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        self::assertEquals($this->parser->getTopics(), $this->parser->getTopics());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser->getTopics());
    }
}
