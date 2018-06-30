<?php

namespace Jikan\Model;

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
     * @param \Jikan\Parser\Person\PublishedMangaParser $parser
     *
     * @return PublishedManga
     */
    public static function fromParser(\Jikan\Parser\Person\PublishedMangaParser $parser): PublishedManga
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
        return $this->role;
    }

    /**
     * @return mangaMeta
     */
    public function getMangaMeta(): mangaMeta
    {
        return $this->mangaMeta;
    }    
}
