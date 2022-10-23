<?php

namespace Jikan\Model\Magazine;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Magazine\MagazineListParser;
use Jikan\Parser\Magazine\MagazineParser;

/**
 * Class Magazine
 *
 * @package Jikan\Model
 */
class MagazineList
{
    /**
     * @var array|MagazineListItem[]
     */
    public $magazines = [];

    /**
     * @param MagazineParser $parser
     *
     * @return Magazine
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(MagazineListParser $parser): self
    {
        $instance = new self();
        $instance->magazines = $parser->getMagazines();

        return $instance;
    }

    /**
     * @return array|MagazineListItem[]
     */
    public function getMagazines()
    {
        return $this->magazines;
    }
}
