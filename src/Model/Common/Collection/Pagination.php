<?php

namespace Jikan\Model\Common\Collection;

/**
 * Class Pagination
 *
 * @property bool $hasNextPage
 * @property int|null $lastVisiblePage
 *
 * @package Jikan\Model
 */
interface Pagination
{
    /**
     * @return bool
     */
    public function hasNextPage() : bool;

    /**
     * @return int|null
     */
    public function getLastVisiblePage() : ?int;
}
