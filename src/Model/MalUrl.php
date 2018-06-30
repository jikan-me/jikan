<?php

namespace Jikan\Model;

/**
 * Class MalUrl
 *
 * @package Jikan\Model
 */
class MalUrl
{
    /**
     * @var int
     */    
    private $malId;
    
    /**
     * @var string
     */
    
     private $type;
    
     /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;



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

        if (preg_match('~https://myanimelist.net/(.*?)/(.*?)/(.*?)~', $this->url, $matches)) {
            $this->type = $matches[1];
            $this->malId = (int) $matches[2];
        }
        
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
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
