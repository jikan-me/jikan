<?php

namespace JikanTest\Parser\Producer;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Producer\ProducerList;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ProducerListParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Producer\ProducerListParser
 * (getProducers) against the full `anime/producer` list page.
 */
class ProducerListParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Producer\ProducerListParser
     */
    private $parser;

    /**
     * @var ProducerList
     */
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Producer\ProducersRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new \Jikan\Parser\Producer\ProducerListParser($crawler);
        $this->model = $this->parser->getModel();
    }

    #[Test]
    public function it_parses_the_producer_list(): void
    {
        self::assertCount(930, $this->model->getProducers());
    }

    #[Test]
    public function it_parses_the_first_and_last_producers(): void
    {
        $producers = $this->model->getProducers();
        self::assertSame('8bit', $producers[0]->getName());
        self::assertSame(441, $producers[0]->getMalId());
        self::assertSame(78, $producers[0]->getCount());

        $last = $producers[count($producers) - 1];
        self::assertSame('ZOOM ENTERPRISE', $last->getName());
        self::assertSame(1376, $last->getMalId());
        self::assertSame(1, $last->getCount());
    }
}