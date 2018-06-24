<?php

namespace Jikan\Helper;

/**
 * Class Episodes
 *
 * @package Jikan\Helper
 */
class Episodes extends \Jikan\Abstracts\Helper
{

    /**
     * Episodes constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param int $page
     */
    public function setPage(int $page)
    {
        $this->offsetSet('p', $page);
    }

    /**
     * @return string
     */
    public function build()
    {
        $query = "";
        foreach ($this->container as $key => $value) {
            $query .= $key."=".$value."&";
        }

        return $query;
    }
}