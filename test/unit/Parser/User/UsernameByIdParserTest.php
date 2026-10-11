<?php

namespace JikanTest\Parser\User;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\User\UsernameByIdParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class UsernameByIdParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\User\UsernameByIdParser
 * (getUsernameById). The comments page for an id links the profile it belongs
 * to; the parser maps that link back to the username + profile URL.
 */
class UsernameByIdParserTest extends TestCase
{
    /**
     * @var UsernameByIdParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\User\UsernameByIdRequest(144050);
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());

        $this->parser = new UsernameByIdParser($crawler);
    }

    #[Test]
    public function it_resolves_the_username_from_the_id(): void
    {
        $user = $this->parser->getUser();
        self::assertSame('Nimbiss', $user->getUsername());
        self::assertSame('https://myanimelist.net/profile/Nimbiss', $user->getUrl());
    }
}