<?php

namespace JikanTest\Parser\User\History;

use JikanTest\TestCase;

/**
 * Class HistoryParserTest
 */
class HistoryParserTest extends TestCase
{
    /**
     * @var \Jikan\Model\User\History[]
     */
    private $parser;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $client = new \Goutte\Client($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/history/nekomata1037/');
        $this->parser = (new \Jikan\Parser\User\History\HistoryParser($crawler))->getModel();
    }

    /**
     * @test
     */
    public function it_gets_the_url()
    {
        self::assertInstanceOf(\Jikan\Model\Common\MalUrl::class, $this->parser[0]->getMalUrl());
        self::assertInternalType('string', $this->parser[0]->getMalUrl()->getTitle());
    }

    /**
     * @test
     */
    public function it_gets_the_increment()
    {
        self::assertInternalType('int', $this->parser[0]->getIncrement());
    }

    /**
     * @test
     */
    public function it_gets_the_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser[0]->getDate());
    }

}