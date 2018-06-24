<?php

namespace Jikan\Exception;

/**
 * Class UnsupportedRequestException
 *
 * @package Jikan\Exception
 */
class UnsupportedRequestException extends \Exception
{
    const DEFAULT_STRING = 'Unsupported Request';

    /**
     * UnsupportedRequestException constructor.
     *
     * @param null           $message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($message = null, int $code = 0, Exception $previous = null)
    {
        if (is_null($message)) {
            $message = self::DEFAULT_STRING;
        }

        parent::__construct($message, $code, $previous);
    }
}
