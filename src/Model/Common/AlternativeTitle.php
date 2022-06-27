<?php

namespace Jikan\Model\Common;

class AlternativeTitle
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $language;

    /**
     * @param string $title
     * @param string $language
     */
    public function __construct(string $title, string $language)
    {
        $this->title = $title;
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
}
