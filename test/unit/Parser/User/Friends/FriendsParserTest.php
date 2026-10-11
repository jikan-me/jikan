<?php

namespace JikanTest\Parser\User\Friends;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\User\Friends\FriendsParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class FriendsParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\User\Friends\FriendsParser
 * (getUserFriends) against a user's friends page.
 */
class FriendsParserTest extends TestCase
{
    /**
     * @var FriendsParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request(
            'GET',
            'https://myanimelist.net/profile/DatRandomDude/friends?p=1'
        );

        $this->parser = new FriendsParser($crawler);
    }

    #[Test]
    public function it_parses_the_friend_list(): void
    {
        self::assertCount(100, $this->parser->getResults());
    }

    #[Test]
    public function it_parses_friend_metadata(): void
    {
        $results = $this->parser->getResults();
        self::assertSame('CarotoMagico', $results[0]->getUser()->getUsername());
        self::assertSame(
            'https://myanimelist.net/profile/CarotoMagico',
            $results[0]->getUser()->getUrl()
        );
        self::assertSame('2018-03-09', $results[0]->getFriendsSince()->format('Y-m-d'));
        self::assertSame('SyverenWaterlow', $results[1]->getUser()->getUsername());
    }

    #[Test]
    public function it_reports_pagination(): void
    {
        self::assertTrue($this->parser->getHasNextPage());
    }
}