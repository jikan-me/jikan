<?php

namespace JikanTest\Parser\Magazine;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Magazine\MagazineList;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class MagazineListParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Magazine\MagazineListParser
 * (getMagazines) against the full `manga/magazine` list page.
 */
class MagazineListParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Magazine\MagazineListParser
     */
    private $parser;

    /**
     * @var MagazineList
     */
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Magazine\MagazinesRequest();
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new \Jikan\Parser\Magazine\MagazineListParser($crawler);
        $this->model = $this->parser->getModel();
    }

    #[Test]
    public function it_parses_the_magazine_list(): void
    {
        self::assertCount(1498, $this->model->getMagazines());
    }

    #[Test]
    public function it_parses_the_first_and_last_magazines(): void
    {
        $magazines = $this->model->getMagazines();
        self::assertSame('&.Emo', $magazines[0]->getName());
        self::assertSame(1658, $magazines[0]->getMalId());
        self::assertSame(28, $magazines[0]->getCount());
        self::assertSame(
            'https://myanimelist.net/manga/magazine/1658/_Emo',
            $magazines[0]->getUrl()
        );

        $last = $magazines[count($magazines) - 1];
        self::assertSame('Zoukan Young Gangan', $last->getName());
        self::assertSame(1161, $last->getMalId());
        self::assertSame(3, $last->getCount());
    }
}