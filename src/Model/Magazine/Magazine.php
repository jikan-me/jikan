<?php

namespace Jikan\Model\Magazine;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Magazine\MagazineParser;

/**
 * Class Magazine
 *
 * @package Jikan\Model
 */
class Magazine extends Results implements Pagination
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $hasNextPage = false;

    /**
     * @var int
     */
    private $lastVisiblePage = 1;

    /**
     * @param MagazineParser $parser
     *
     * @return Magazine
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(MagazineParser $parser): self
    {
        $instance = new self();

        $instance->malId = $parser->getUrl()->getMalId();
        $instance->url = $parser->getUrl()->getUrl();
        $instance->name = $parser->getUrl()->getName();
        $instance->results = $parser->getResults();
        $instance->hasNextPage = $parser->getHasNextPage();
        $instance->lastVisiblePage = $parser->getLastPage();

        return $instance;
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->hasNextPage;
    }

    /**
     * @return int
     */
    public function getLastVisiblePage(): int
    {
        return $this->lastVisiblePage;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
