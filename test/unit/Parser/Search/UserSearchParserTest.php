<?php

namespace JikanTest\Parser\Search;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Search\UserSearchParser;
use JikanTest\TestCase;
use JikanTest\ModelSnapshot;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class UserSearchParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Search\UserSearchParser, the parser
 * behind getUserSearch() (users.php?q=...) and getRecentOnlineUsers()
 * (users.php). The search page is parsed in setUp; the recently-online page in
 * its own test.
 */
class UserSearchParserTest extends TestCase
{
    /**
     * @var UserSearchParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Search\UserSearchRequest('irfan');
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new UserSearchParser($crawler);
    }

    #[Test]
    public function it_parses_the_user_search_page(): void
    {
        $results = $this->parser->getResults();
        self::assertCount(24, $results);

        $first = $results[0];
        self::assertSame('irfanhamka', $first->getUsername());
        self::assertSame('https://myanimelist.net/profile/irfanhamka', $first->getUrl());

        // Results with a default avatar carry MAL's kaomoji image; find one
        // with real user artwork to prove the URL keeps flowing through.
        $avatar = null;
        foreach ($results as $result) {
            $image = $result->getImages()->getJpg()->getImageUrl();
            if ($image !== null && str_contains($image, 'userimages')) {
                $avatar = $image;
                break;
            }
        }

        self::assertNotNull($avatar);
        self::assertMatchesRegularExpression('~https://cdn\.myanimelist\.net/s/common/userimages/.*~', $avatar);
    }

    #[Test]
    public function it_reports_search_pagination(): void
    {
        self::assertTrue($this->parser->getHasNextPage());
        self::assertSame(20, $this->parser->getLastPage());
    }

    #[Test]
    public function it_parses_the_recently_online_page(): void
    {
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/users.php');
        $parser = new UserSearchParser($crawler);
        $results = $parser->getResults();

        self::assertCount(20, $results);
        self::assertSame('-Auri-', $results[0]->getUsername());
        self::assertStringStartsWith(
            'https://myanimelist.net/profile/',
            $results[0]->getUrl()
        );
    }

    #[Test]
    public function it_builds_the_same_model_when_parsed_twice(): void
    {
        // Relative dates ("5 minutes ago") resolve against the clock at parse time, so two
        // parses differ by microseconds; allow a small delta instead of exact equality.
        self::assertEqualsWithDelta($this->parser->getModel(), $this->parser->getModel(), 5.0);
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parser->getModel(), true);
    }
}
