<?php

namespace Jikan\Model;

/**
 * Class Aired
 *
 * @package Jikan\Model
 */
class Aired
{
    /**
     * @var \DateTimeImmutable|null
     */
    private $from;

    /**
     * @var \DateTimeImmutable|null
     */
    private $until;

    /**
     * Aired constructor.
     *
     * @param \DateTimeImmutable|null $from
     * @param \DateTimeImmutable|null $until
     */
    public function __construct(?\DateTimeImmutable $from, ?\DateTimeImmutable $until)
    {
        $this->from = $from;
        $this->until = $until;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getFrom(): ?\DateTimeImmutable
    {
        return $this->from;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUntil(): ?\DateTimeImmutable
    {
        return $this->until;
    }
}
