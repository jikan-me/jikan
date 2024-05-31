<?php

namespace JikanTest\Parser\UserHistory;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class HistoryParserTest
 */
class UserHistoryParserTest extends TestCase
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

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/history/morshuwarrior/');
        $this->parser = (new \Jikan\Parser\User\History\HistoryParser($crawler))->getModel();
    }

    #[Test]
    public function it_gets_the_url()
    {
        self::assertInstanceOf(\Jikan\Model\Common\MalUrl::class, $this->parser[0]->getMalUrl());
        self::assertIsString($this->parser[0]->getMalUrl()->getTitle());
    }

    #[Test]
    public function it_gets_the_increment()
    {
        self::assertIsInt($this->parser[0]->getIncrement());
    }

    #[Test]
    public function it_gets_the_date()
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $this->parser[0]->getDate());
    }

}
