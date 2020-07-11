<?php

namespace Jikan\Model\Producer;

use Jikan\Model\Common\MalUrl;
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
    public $malUrl;

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
        $instance->malUrl = $parser->getUrl();
        $instance->anime = $parser->getProducerAnime();

        return $instance;
    }

    /**
     * @return MalUrl
     */
    public function getMalUrl(): MalUrl
    {
        return $this->malUrl;
    }

    /**
     * @return array|ProducerAnime[]
     */
    public function getAnime(): array
    {
        return $this->anime;
    }
}
