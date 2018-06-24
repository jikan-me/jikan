<?php

namespace Jikan\Request;

/**
 * Interface RequestInterface
 *
 * @package Jikan\Request
 */
interface RequestInterface
{
    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string;
}
