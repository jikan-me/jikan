<?php

namespace Jikan\Model\Common;

use Jikan\Helper\MalUrlExtractor;
use Jikan\Helper\Parser;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Parser\Common\MalUrlParser;

/**
 * Class Recommendation
 *
 * @package Jikan\Model\Common
 */
class Recommendation
{
    /**
     * @var CommonMeta
     */
    private $entry;

    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $votes;

    /**
     * @param \Jikan\Parser\Common\Recommendation $parser
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public static function fromParser(\Jikan\Parser\Common\Recommendation $parser): self
    {
        $instance = new self();

        $instance->entry = $parser->getEntryMeta();
        $instance->url = $parser->getRecommendationurl();
        $instance->votes = $parser->getRecommendationCount();

        return $instance;
    }

    /**
     * @return CommonMeta
     */
    public function getEntry(): CommonMeta
    {
        return $this->entry;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getVotes(): int
    {
        return $this->votes;
    }
}
