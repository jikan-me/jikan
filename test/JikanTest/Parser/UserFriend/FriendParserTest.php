<?php

namespace JikanTest\Parser\User\Friends;

use JikanTest\TestCase;

class FriendParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\User\Friends\FriendParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/profile/morshuwarrior/friends');
        $this->parser = new \Jikan\Parser\User\Friends\FriendParser(
            $crawler->filterXPath(
                '//div[contains(@class, "boxlist-container")]/div[contains(@class, "boxlist")][3]'
            )
        );
    }

    /**
     * @test
     */
    public function it_gets_the_name()
    {
        self::assertEquals('batsling1234', $this->parser->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/profile/batsling1234', $this->parser->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_avatar()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/userimages/4723677.jpg?t=1654526400',
            $this->parser->getAvatar()
        );
    }

    /**
     * @test
     */
    public function it_gets_friends_since()
    {
        self::assertEquals(
            '2019-08-03 18:25',
            $this->parser->getFriendsSince()->format('Y-m-d H:i')
        );
    }
    /**
     * @test
     */
    public function it_gets_last_online()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser->getLastOnline());
    }
}