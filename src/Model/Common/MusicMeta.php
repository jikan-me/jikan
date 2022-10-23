<?php

namespace Jikan\Model\Common;

/**
 * Class MusicMeta
 *
 * @package Jikan\Model\Common
 */
class MusicMeta
{
    /**
     * @var string|null
     */
    private ?string $title;


    /**
     * @var string|null
     */
    private ?string $author;

    /**
     * @param string|null $title
     * @param string|null $author
     */
    public function __construct(?string $title, ?string $author)
    {
        $this->title = $title;
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "$this->title by $this->author";
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }
}
