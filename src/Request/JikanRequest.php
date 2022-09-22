<?php

namespace Jikan\Request;

use Goutte\Client;
use Jikan\Exception\BadResponseException;
use Jikan\Goutte\GoutteWrapper;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class GoutteWrapper
 *
 * @package Jikan\Goutte
 */
class JikanRequest
{
    public static function create(
        RequestInterface $request
    ) : Crawler
    {
        return (new HttpBrowser(
            $client ?? HttpClient::create(),
            null,
            (new CookieJar())
                ->set(new Cookie('reviews_inc_spoilers', 1))
        ))->request('GET', $request->getPath());
    }
}
