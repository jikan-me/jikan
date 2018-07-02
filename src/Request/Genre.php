<?php

namespace Jikan\Request;

class Genre implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $string;

    /**
     * @var int
     */
    private $page;

    /**
     * Genre constructor.
     *
     * @param int $id
     * @param int $page
     *
     */
    public function __construct(int $id, string $type, int $page = 1)
    {
        if (!\in_array($type, ['anime', 'manga'])) {
            throw new \InvalidArgumentException(sprintf('Type %s is not valid', $type));
        }

        $this->id = $id;
        $this->type = $type;
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/%s/genre/%s?page=%s', $this->type, $this->id, $this->page);
    }
}
