<?php

namespace JikanTest\Parser\User\Friends;

use PHPUnit\Framework\TestCase;

class FriendParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\User\Friends\FriendParser
     */
    private $parser;

    public function setUp(): void
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/profile/morshuwarrior/friends');
        $this->parser = new \Jikan\Parser\User\Friends\FriendParser(
            $crawler->filterXPath(
                '//div[contains(@class, "friendBlock")][3]'
            )
        );
    }

    /**
     * @test
     */
    public function it_gets_the_name()
    {
        self::assertEquals('HeavyGod', $this->parser->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/profile/HeavyGod', $this->parser->getUrl());
    }

    /**
     * @test
     */
    public function it_gets_the_avatar()
    {
        self::assertEquals(
            'https://cdn.myanimelist.net/images/userimages/6574969.jpg?t=1610778600',
            $this->parser->getAvatar()
        );
    }

    /**
     * @test
     */
    public function it_gets_friends_since()
    {
        self::assertEquals(
            '2018-01-16 07:34',
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