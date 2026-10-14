<?php

namespace JikanTest;

use Jikan\Exception\ParserException;
use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Resource\PersonImageResource\Jpg as PersonJpg;
use Jikan\Model\Resource\UserImageResource\Jpg as UserJpg;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\RequestInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\BrowserKit\History;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * Regressions found by static analysis: code paths that always failed at runtime.
 */
class MalClientRegressionTest extends BaseTestCase
{
    #[Test]
    public function get_news_list_uses_the_http_client_it_was_given(): void
    {
        // getNewsList() used to read a non-existent $this->ghoutte property and fail before any request.
        $client = new MalClient(new MockHttpClient(new MockResponse('<html><body></body></html>')));
        $request = new class implements RequestInterface {
            public function getPath(): string
            {
                return 'https://myanimelist.net/news';
            }
        };

        try {
            $client->getNewsList($request);
        } catch (ParserException) {
            // An empty page may legitimately fail to parse; reaching the parser is what this test proves.
        }

        $this->addToAssertionCount(1);
    }

    #[Test]
    public function http_client_wrapper_does_not_accumulate_history(): void
    {
        $wrapper = new HttpClientWrapper(new MockHttpClient(fn () => new MockResponse('<html><body></body></html>')));

        for ($i = 0; $i < 25; $i++) {
            $wrapper->request('GET', 'https://myanimelist.net/anime/' . $i);
        }

        $stack = (new \ReflectionProperty(History::class, 'stack'))->getValue($wrapper->getHistory());
        self::assertCount(0, $stack);
    }

    #[Test]
    public function person_image_jpg_casts_to_its_url(): void
    {
        self::assertSame('https://cdn.myanimelist.net/a.jpg', (string) PersonJpg::factory('https://cdn.myanimelist.net/a.jpg'));
        self::assertSame('', (string) PersonJpg::factory(null));
    }

    #[Test]
    public function user_image_jpg_casts_to_its_url(): void
    {
        self::assertSame('https://cdn.myanimelist.net/b.jpg', (string) UserJpg::factory('https://cdn.myanimelist.net/b.jpg'));
        self::assertSame('', (string) UserJpg::factory(null));
    }
}
