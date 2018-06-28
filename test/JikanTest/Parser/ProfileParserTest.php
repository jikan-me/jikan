<?php

use PHPUnit\Framework\TestCase;

/**
 * Class ProfileParserTest
 */
class ProfileParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Profile
     */
    private $parser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/profile/sandshark');
        $this->parser = new \Jikan\Parser\UserProfile($crawler);
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_username()
    {
        self::assertEquals('sandshark', $this->parser->getUsername());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_url()
    {
        self::assertEquals('https://myanimelist.net/profile/sandshark', $this->parser->getProfileUrl());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_image()
    {
        self::assertEquals('https://myanimelist.cdn-dena.com/images/userimages/3600201.jpg', $this->parser->getImageUrl());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_join_date()
    {
        self::assertEquals('Feb 26, 2014', $this->parser->getJoinDate());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_last_online()
    {
        self::assertEquals('Now', $this->parser->getLastOnline());
    }

    /**
     * @test
     * @vcr ProfileParserTest.yaml
     */
    public function it_gets_the_gender()
    {
        self::assertEquals('Male', $this->parser->getGender());
    }
}
