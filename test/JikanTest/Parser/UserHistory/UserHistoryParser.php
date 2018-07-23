<?php

namespace JikanTest\Parser\User\History;

use PHPUnit\Framework\TestCase;

/**
 * Class HistoryParserTest
 */
class HistoryParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\User\History\HistoryParser
     */
    private $parser;

    public function setUp()
    {
        $client = new \Goutte\Client();
        $crawler = $client->request('GET', 'https://myanimelist.net/history/nekomata1037');
        $this->parser = (new \Jikan\Parser\User\History\HistoryParser($crawler))->getModel();
    }

    /**
     * @test
     * @vcr HistoryParserTest.yaml
     */
    public function it_gets_the_url()
    {
        self::assertInstanceOf(\Jikan\Model\Common\MalUrl::class, $this->parser[0]->getUrl());
        self::assertEquals('Imouto sae Ireba Ii.', $this->parser[0]->getUrl()->getTitle());
    }

    /**
     * @test
     * @vcr HistoryParserTest.yaml
     */
    public function it_gets_the_increment()
    {
        self::assertEquals(12, $this->parser[0]->getIncrement());
    }

    /**
     * @test
     * @vcr HistoryParserTest.yaml
     */
    public function it_gets_the_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser[0]->getDate());
    }

}