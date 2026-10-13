<?php

namespace Jikan\Http;

use Jikan\Exception\BadResponseException;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class HttpClientWrapper
 *
 * @package Jikan\Http
 */
class HttpClientWrapper extends HttpBrowser
{
    /**
     * @inheritdoc
     * @param      string      $method
     * @param      string      $uri
     * @param      array       $parameters
     * @param      array       $files
     * @param      array       $server
     * @param      string|null $content
     * @param      bool        $changeHistory
     * @return     Crawler
     * @throws     BadResponseException
     */
    public function request(
        string $method,
        string $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        ?string $content = null,
        bool $changeHistory = false
    ): Crawler {
        $response = parent::request(
            $method,
            $uri,
            $parameters,
            $files,
            $server,
            $content,
            $changeHistory
        );

        // Jikan only requests absolute URLs and never navigates back, so history is dead weight. It
        // grew by one entry per request for the lifetime of the client, a slow leak in long-running
        // workers (redirects can still add entries, so clear rather than rely on the default alone).
        $this->history->clear();

        $internalResponse = $this->getInternalResponse();
        if ($internalResponse->getStatusCode() >= 400) {
            throw new BadResponseException(
                $internalResponse->getStatusCode() . ' on ' . $response->getUri(),
                $internalResponse->getStatusCode()
            );
        }

        return $response;
    }
}
