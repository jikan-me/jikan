<?php

namespace Jikan\Model\Common;

/**
 * Class ItemBasicMeta
 *
 * @package Jikan\Model
 */
class ItemBasicMeta
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $name;

    /**
     * ItemBasicMeta constructor.
     *
     * @param string $malId
     * @param string $name
     */
    public function __construct(string $malId, string $name)
    {
        $this->malId = (int) $malId;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
