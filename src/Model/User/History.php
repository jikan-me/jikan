<?php

namespace Jikan\Model\User;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser\User\History\HistoryItemParser;

/**
 * Class History
 *
 * @package Jikan\Model
 */
class History
{
    /**
     * @var MalUrl
     */
    private $malUrl;

    /**
     * @var int
     */
    private $increment;

    /**
     * @var \DateTimeImmutable
     */
    private $date;


    /**
     * @param HistoryItemParser $parser
     *
     * @return History
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function fromParser(HistoryItemParser $parser): self
    {
        $instance = new self();
        $instance->malUrl = $parser->getUrl();
        $instance->increment = $parser->getIncrement();
        $instance->date = $parser->getDate();

        return $instance;
    }


    /**
     * @return MalUrl
     */
    public function getMalUrl(): MalUrl
    {
        return $this->malUrl;
    }

    /**
     * @return int
     */
    public function getIncrement(): int
    {
        return $this->increment;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
}
