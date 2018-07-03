<?php

namespace Jikan\Model;

use Jikan\Parser\Magazine\MagazineParser;

/**
 * Class Magazine
 *
 * @package Jikan\Model
 */
class Magazine
{

    /**
     * @var MalUrl
     */
    public $url;

    /**
     * @var array|MagazineManga[]
     */
    public $manga = [];

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
        $instance->url = $parser->getUrl();
        $instance->manga = $parser->getMagazineManga();

        return $instance;
    }

    /**
     * @return MalUrl
     */
    public function getUrl(): MalUrl
    {
        return $this->url;
    }

    /**
     * @return array|MagazineManga[]
     */
    public function getManga(): array
    {
        return $this->manga;
    }
}
