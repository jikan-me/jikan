<?php

namespace Jikan\Goutte;

use Goutte\Client;
use Jikan\Exception\BadResponseException;

/**
 * Class GoutteWrapper
 *
 * @package Jikan\Goutte
 */
class GoutteWrapper extends Client
{
    /**
     * @inheritdoc
     * @param string $method
     * @param string $uri
     * @param array $parameters
     * @param array $files
     * @param array $server
     * @param string|null $content
     * @param bool $changeHistory
     * @return \Symfony\Component\DomCrawler\Crawler
     * @throws BadResponseException
     */
    public function request(
        string $method,
        string $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        string $content = null,
        bool $changeHistory = true
    ) {
        $response = parent::request(
            $method,
            $uri,
            $parameters,
            $files,
            $server,
            $content,
            $changeHistory
        );

        $internalResponse = $this->getInternalResponse();
        if ($internalResponse->getStatus() >= 400) {
            throw new BadResponseException(
                $internalResponse->getStatus().' on '.$response->getUri(),
                $internalResponse->getStatus()
            );
        }

        return $response;
    }
}
