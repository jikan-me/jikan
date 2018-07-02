<?php

namespace Jikan\Model;

use Jikan\Parser\Producer\ProducerParser;

/**
 * Class Producer
 *
 * @package Jikan\Model
 */
class Producer
{

    /**
     * @var MalUrl
     */
    public $url;

    /**
     * @var array|ProducerAnime[]
     */
    public $anime = [];

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
        $instance->url = $parser->getUrl();
        $instance->anime = $parser->getProducerAnime();

        return $instance;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->season;
    }

    /**
     * @return array|ProducerAnime[]
     */
    public function getAnime(): array
    {
        return $this->anime;
    }
}
