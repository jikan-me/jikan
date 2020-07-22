<?php

namespace Jikan\Model\Recommendations;

use Jikan\Helper\Parser;
use Jikan\Model\Common\CommonMeta;

/**
 * Class Recommendation
 *
 * @package Jikan\Model\Common
 */
class RecommendationListItem
{
    /**
     * @var CommonMeta[]
     */
    private $recommendations;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTimeImmutable|null
     */
    private $date;

    /**
     * @var Recommender
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

        $instance->recommendations = $parser->getRecommendations();
        $instance->content = $parser->getContent();
        $instance->recommender = $parser->getRecommender();
        $instance->date = $parser->getDate();

        return $instance;
    }


}
