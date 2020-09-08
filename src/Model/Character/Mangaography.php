<?php

namespace Jikan\Model\Character;

use Jikan\Model\Common\MangaMeta;
use Jikan\Model\Common\Ography;
use Jikan\Parser\Character\MangaographyParser;

/**
 * Class MangaographyParser
 *
 * @package Jikan\Model
 */
class Mangaography extends Ography
{
    /**
     * @var MangaMeta
     */
    private $mangaMeta;

    /**
     * @param MangaographyParser $parser
     *
     * @return Mangaography
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MangaographyParser $parser): Mangaography
    {
        $instance = new self();

        $instance->mangaMeta = $parser->getMangaMeta();
        $instance->role = $parser->getRole();

        return $instance;
    }
}
