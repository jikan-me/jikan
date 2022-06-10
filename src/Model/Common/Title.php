<?php

namespace Jikan\Model\Common;

class Title
{
    const TYPE_DEFAULT = 'Default';
    const TYPE_SYNONYM = 'Synonym';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $title;

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
