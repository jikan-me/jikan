<?php

namespace Jikan\Model\Recommendations;

use Jikan\Helper\Parser;

/**
 * Class Recommendation
 *
 * @package Jikan\Model\Common
 */
class RecommendationListItem
{
    /**
     * @var AnimeMeta[]
     */
    private $anime;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTimeImmutable|null
     */
    private $date;

    /**
     * @var User
     */
    private $recommender;

    /**
     * @param \Jikan\Parser\Common\Recommendation $parser
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public static function fromParser(\Jikan\Parser\Recommendations\RecommendationListItemParser $parser): self
    {
        $instance = new self();

        $instance->anime = $parser->getAnime();
        $instance->content = $parser->getContent();
        $instance->recommender = $parser->getRecommender();
        $instance->date = $parser->getDate();

        return $instance;
    }

    /**
     * @return AnimeMeta[]
     */
    public function getAnime(): array
    {
        return $this->anime;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return User
     */
    public function getRecommender(): User
    {
        return $this->recommender;
    }
}
