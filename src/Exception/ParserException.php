<?php

namespace Jikan\Exception;

use Jikan\Request\RequestInterface;

/**
 * Class ParserException
 *
 * @package Jikan\Exception
 */
class ParserException extends \Exception
{
    /**
     * @param RequestInterface $request
     * @param \Exception       $previous
     *
     * @return ParserException
     */
    public static function fromRequest(RequestInterface $request, \Exception $previous): ParserException
    {
        $message = sprintf('Failed to parse \'%s\'', $request->getPath());

        return new self($message, $previous->getCode(), $previous);
    }
}
