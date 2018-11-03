<?php

namespace Jikan\Model\Common;

use Jikan\Helper\Parser;

/**
 * Class Recommendation
 *
 * @package Jikan\Model\Common
 */
class Recommendation
{

    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $recommendationUrl;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $recommendationCount;

    /**
     * @param \Jikan\Parser\Common\Recommendation $parser
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public static function fromParser(\Jikan\Parser\Common\Recommendation $parser): self
    {
        $instance = new self();

        $instance->url = $parser->getUrl();
        $instance->malId = Parser::idFromUrl($instance->url);
        $instance->imageUrl = $parser->getImageUrl();
        $instance->recommendationUrl = $parser->getRecommendationurl();
        $instance->title = $parser->getTitle();
        $instance->recommendationCount = $parser->getRecommendationCount();

        return $instance;
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
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getRecommendationUrl(): string
    {
        return $this->recommendationUrl;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getRecommendationCount(): int
    {
        return $this->recommendationCount;
    }
}
