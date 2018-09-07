<?php

namespace JikanTest\Parser\User\Friends;

use PHPUnit\Framework\TestCase;

class FriendParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\User\Friends\FriendParser
     */
    private $parser;

    public function setUp()
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
     * @vcr FriendsParserTest.yaml
     */
    public function it_gets_the_name()
    {
        self::assertEquals('Dinoe', $this->parser->getName());
    }

    /**
     * @test
     * @vcr FriendsParserTest.yaml
     */
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/profile/Dinoe', $this->parser->getUrl());
    }

    /**
     * @test
     * @vcr FriendsParserTest.yaml
     */
    public function it_gets_the_avatar()
    {
        self::assertEquals(
            'https://myanimelist.cdn-dena.com/images/userimages/5082596.jpg',
            $this->parser->getAvatar()
        );
    }

    /**
     * @test
     * @vcr FriendsParserTest.yaml
     */
    public function it_gets_friends_since()
    {
        self::assertEquals(
            '2016-05-11 04:37',
            $this->parser->getFriendsSince()->format('Y-m-d H:i')
        );
    }
    /**
     * @test
     * @vcr FriendsParserTest.yaml
     */
    public function it_gets_last_online()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser->getLastOnline());
    }
}