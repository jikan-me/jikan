<?php

namespace Jikan\Model\Manga;

use Jikan\Model\Common\MalUrl;

/**
 * A manga's author, with the role MyAnimeList prints beside their name — e.g. "Story & Art" for
 * "Urasawa, Naoki (Story & Art)".
 *
 * @package Jikan\Model
 */
class MangaAuthor
{
    /**
     * @var MalUrl
     */
    private MalUrl $author;

    /**
     * @var string|null
     */
    private ?string $role;

    /**
     * @param MalUrl      $author
     * @param string|null $role
     */
    public function __construct(MalUrl $author, ?string $role)
    {
        $this->author = $author;
        $this->role = $role;
    }

    /**
     * @return int|string
     */
    public function getMalId(): int|string
    {
        return $this->author->getMalId();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->author->getName();
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->author->getUrl();
    }

    /**
     * The role printed after the author's name, e.g. "Story", "Art", "Story & Art"; null when
     * MyAnimeList prints none.
     *
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }
}
