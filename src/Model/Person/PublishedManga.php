<?php

namespace Jikan\Model\Person;

use Jikan\Model\Common\MangaMeta;
use Jikan\Parser\Person\PublishedMangaParser;

/**
 * Class PublishedManga
 *
 * @package Jikan\Model
 */
class PublishedManga
{
    /**
     * @var string
     */
    private $position;

    /**
     * @var mangaMeta
     */
    private $mangaMeta;


    /**
     * @param PublishedMangaParser $parser
     *
     * @return PublishedManga
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(PublishedMangaParser $parser): PublishedManga
    {
        $instance = new self();
        $instance->position = $parser->getPosition();
        $instance->mangaMeta = $parser->getMangaMeta();

        return $instance;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @return mangaMeta
     */
    public function getMangaMeta(): MangaMeta
    {
        return $this->mangaMeta;
    }
}
