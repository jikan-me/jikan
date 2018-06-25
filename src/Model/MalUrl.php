<?php

namespace Jikan\Model;

class MalUrl
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Genre constructor.
     *
     * @param string $name
     * @param string $url
     */
    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
