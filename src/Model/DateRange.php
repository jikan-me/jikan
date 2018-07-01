<?php

namespace Jikan\Model;

use Jikan\Helper\Parser;

/**
 * Class DateRange
 *
 * @package Jikan\Model
 */
class DateRange
{
    /**
     * @var string
     */
    private $aired;

    /**
     * DateRange constructor.
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
        if (strpos($aired, ' to ') !== false || strpos($aired, ' to ?') !== false) {
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
        $aired = explode(' to ', $aired)[1];

        return Parser::parseDate($aired);
    }
}
