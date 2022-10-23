<?php

namespace Jikan\Model\Producer;

use Jikan\Parser\Producer\ProducerListParser;
use Jikan\Parser\Producer\ProducerParser;

/**
 * Class Producer
 *
 * @package Jikan\Model
 */
class ProducerList
{
    /**
     * @var array|ProducerListItem[]
     */
    public $producers = [];

    /**
     * @param ProducerParser $parser
     *
     * @return Producer
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(ProducerListParser $parser): self
    {
        $instance = new self();
        $instance->producers = $parser->getProducers();

        return $instance;
    }

    /**
     * @return array|ProducerListItem[]
     */
    public function getProducers()
    {
        return $this->producers;
    }
}
