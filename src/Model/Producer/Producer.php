<?php

namespace Jikan\Model\Producer;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\Url;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Model\Resource\WrapImageResource\WrapImageResource;
use Jikan\Model\Top\TopPeople;
use Jikan\Parser\Producer\ProducerParser;

/**
 * Class Producer
 *
 * @package Jikan\Model
 */
class Producer extends Results implements Pagination
{
    /**
     * @var int
     */
    private int $malId;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var WrapImageResource
     */
    private WrapImageResource $images;

    /**
     * @var string
     * @deprecated @see titles
     */
    private string $name;

    /**
     * @var array
     */
    private array $titles;

    /**
     * @var \DateTimeImmutable|null
     */
    private ?\DateTimeImmutable $established;

    /**
     * @var int|null
     */
    private ?int $favorites;

    /**
     * @var string|null
     */
    private ?string $about;

    /**
     * @var Url[]
     */
    private array $externalLinks;

    /**
     * @var int
     */
    private int $count;

    /**
     * @var bool
     */
    private bool $hasNextPage = false;

    /**
     * @var int
     */
    private int $lastVisiblePage = 1;

    /**
     * @param ProducerParser $parser
     *
     * @return Producer
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(ProducerParser $parser): self
    {
        $instance = new self();

        $instance->malId = $parser->getUrl()->getMalId();
        $instance->url = $parser->getUrl()->getUrl();
        $instance->titles = $parser->getTitles();
        $instance->name = (string) $instance->titles[0]->getTitle();
        $instance->images = $parser->getImages();
        $instance->favorites = $parser->getFavorites();
        $instance->established = $parser->getEstablished();
        $instance->about = $parser->getAbout();
        $instance->externalLinks = $parser->getExternalLinks();
        $instance->count = $parser->getAnimeCount();
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
     * @return WrapImageResource
     */
    public function getImages(): WrapImageResource
    {
        return $this->images;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getEstablished(): ?\DateTimeImmutable
    {
        return $this->established;
    }

    /**
     * @return int|null
     */
    public function getFavorites(): ?int
    {
        return $this->favorites;
    }

    /**
     * @return string|null
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }

    /**
     * @return Url[]
     */
    public function getExternalLinks(): array
    {
        return $this->externalLinks;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
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
}
