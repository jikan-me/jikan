<?php

namespace Jikan\Model\Common;

/**
 * Class Title represents an anime/manga title which has a type. A type can be
 * 'Default', 'Synonym', or a language (e.g. 'English', 'German', 'Japanese').
 *
 * @package Jikan\Model\Common
 */
class Title
{
    const TYPE_DEFAULT = 'Default';
    const TYPE_SYNONYM = 'Synonym';

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $title;

    /**
     * @param string $title
     * @param string $type
     */
    public function __construct(string $type, string $title)
    {
        $this->type = $type;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
