<?php

namespace Jikan\Request\Search;

use Jikan\Exception\BadResponseException;
use Jikan\Helper\Constants;
use Jikan\Request\RequestInterface;

/**
 * Class UserSearchRequest
 *
 * @package Jikan\Request\Search
 */
class UserSearchRequest implements RequestInterface
{
    /**
     * @var string|null
     */
    private $query;

    /**
     * @var int
     */
    private $page;

    /**
     * Advanced Search
     */

    /**
     * @var string
     */
    private $location;

    /**
     * @var int
     */
    private $minAge = 0;

    /**
     * @var int
     */
    private $maxAge = 0;

    /**
     * @var int
     */
    private $gender = Constants::SEARCH_USER_GENDER_ANY;


    public function __construct(?string $query = null, int $page = 1)
    {
        $this->query = $query;
        $this->page = $page;

        $this->query = $this->query ?? '';

        $querySize = strlen($this->query);

        if ($querySize > 0 && $querySize < 3) {
            throw new BadResponseException('Search with queries require at least 3 characters');
        }
    }

    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string
    {
        $query = http_build_query(
            [
                'q'      => $this->query,
                'show'   => ($this->page !== 1) ? 24 * ($this->page - 1) : null,
                'loc'   => $this->location,
                'agelow'  => $this->minAge,
                'agehigh' => $this->maxAge,
                'g'      => $this->gender,
            ]
        );

        return sprintf(
            'https://myanimelist.net/users.php?%s',
            $query
        );
    }

    /**
     * @param string|null $query
     * @return UserSearchRequest
     */
    public function setQuery(?string $query): UserSearchRequest
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param int $page
     * @return UserSearchRequest
     */
    public function setPage(?int $page): UserSearchRequest
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @param string|null $location
     * @return UserSearchRequest
     */
    public function setLocation(?string $location): UserSearchRequest
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @param int|null $minAge
     * @return UserSearchRequest
     */
    public function setMinAge(?int $minAge): UserSearchRequest
    {
        $this->minAge = $minAge;
        return $this;
    }

    /**
     * @param int|null $maxAge
     * @return UserSearchRequest
     */
    public function setMaxAge(?int $maxAge): UserSearchRequest
    {
        $this->maxAge = $maxAge;
        return $this;
    }

    /**
     * @param int|null $gender
     * @return UserSearchRequest
     */
    public function setGender(?int $gender): UserSearchRequest
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return int
     */
    public function getMinAge(): int
    {
        return $this->minAge;
    }

    /**
     * @return int
     */
    public function getMaxAge(): int
    {
        return $this->maxAge;
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }
}
