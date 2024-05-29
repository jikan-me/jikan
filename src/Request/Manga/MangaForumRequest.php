<?php

namespace Jikan\Request\Manga;

use Jikan\Request\RequestInterface;

/**
 * Class MangaForumRequest
 *
 * @package Jikan\Request
 */
class MangaForumRequest implements RequestInterface
{
    /**
     * @var array
     */
    private static $validTypes = ['all', 'episode', 'other'];

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $topic;

    /**
     * MangaForumRequest constructor.
     *
     * @param int         $id
     * @param string|null $topic
     */
    public function __construct(int $id, ?string $topic = null)
    {
        $this->id = $id;
        $this->topic = $topic;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $query = '';
        if ($this->topic !== null && \in_array($this->topic, self::$validTypes, true)) {
            $query = '?'.http_build_query(['topic' => $this->topic]);
        }

        return sprintf('https://myanimelist.net/manga/%s/_/forum%s', $this->id, $query);
    }

    /**
     * @return array
     */
    public static function getValidTypes(): array
    {
        return self::$validTypes;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTopic(): ?string
    {
        return $this->topic;
    }
}
