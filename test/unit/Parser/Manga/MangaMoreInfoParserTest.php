<?php

namespace JikanTest\Parser\Manga;

use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class MangaMoreInfoParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\Manga\MoreInfoParser
 * (getMangaMoreInfo) against Berserk's moreinfo page.
 */
class MangaMoreInfoParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Manga\MoreInfoParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\Manga\MangaMoreInfoRequest(2);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new \Jikan\Parser\Manga\MoreInfoParser($crawler);
    }

    #[Test]
    public function it_gets_the_more_info_text(): void
    {
        self::assertStringContainsString(
            'Berserk: The Prototype (1988)',
            (string) $this->parser->getMoreInfo()
        );
    }
}