<?php

namespace Jikan\Model;

use Jikan\Helper\Parser;

/**
 * Class Aired
 *
 * @package Jikan\Model
 */
class Aired
{
    /**
     * @var string
     */
    private $aired;

    /**
     * Aired constructor.
     *
     * @param string $aired
     */
    public function __construct(string $aired)
    {
        $this->aired = $aired;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getFrom(): ?\DateTimeImmutable
    {
        $aired = $this->aired;
        if ($aired === 'Not available') {
            return null;
        }
        if (strpos($aired, ' to ') === false || strpos($aired, ' to ?') !== false) {
            $aired = explode(' to ', $aired)[0];
        }

        return Parser::parseDate($aired);
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUntil(): ?\DateTimeImmutable
    {
        $aired = $this->aired;
        if (strpos($aired, ' to ') === false || strpos($aired, ' to ?') !== false) {
            return null;
        }

        return Parser::parseDate(explode(' to ', $aired)[1]);
    }
}
