<?php

namespace Jikan\Request\Person;

use Jikan\Request\RequestInterface;

/**
 * Class PersonPicturesRequest
 *
 * @package Jikan\Request
 */
class PersonPicturesRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * PersonPicturesRequest constructor.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        // MyAnimeList wants <something> after /<id>/... it happily accepts jikan as a valid parameter though
        return sprintf('https://myanimelist.net/people/%d/jikan/pics', $this->id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
