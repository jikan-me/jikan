<?php

namespace JikanTest\Parser\UserFriend;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FriendParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\User\Friends\FriendParser
     */
    private \Jikan\Parser\User\Friends\FriendParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/profile/nekomata1037/friends');
        $this->parser = new \Jikan\Parser\User\Friends\FriendParser(
            $crawler->filterXPath(
                '//div[contains(@class, "boxlist-container")]/div[contains(@class, "boxlist")][3]'
            )
        );
    }

    #[Test]
    public function it_gets_the_name()
    {
        self::assertEquals('sandshark', $this->parser->getName());
    }

    #[Test]
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/profile/sandshark', $this->parser->getUrl());
    }

    #[Test]
    public function it_gets_the_avatar()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/s/common/userimages/7168664d-c9f3-4e6c-9936-bf7b3cb25e6b_76w?s=2f3e2ce6c0bed17cb41fd558d269f311',
            $this->parser->getAvatar()
        );
    }

    #[Test]
    public function it_gets_friends_since()
    {
        self::assertEquals(
            '2018-07-26 08:45',
            $this->parser->getFriendsSince()->format('Y-m-d H:i')
        );
    }
    #[Test]
    public function it_gets_last_online()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser->getLastOnline());
    }
}
