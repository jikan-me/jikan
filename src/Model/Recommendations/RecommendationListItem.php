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
     * The COMPOUND key of a recommendation pair: "{entry[0].malId}-{entry[1].malId}".
     * Intentionally a string, not an int — a single MAL id cannot identify a pair.
     *
     * @var string
     */
    private string $malId;

    /**
     * @var CommonMeta[]
     */
    private $entry;

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
    private $user;

    /**
     * @param \Jikan\Parser\Common\Recommendation $parser
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public static function fromParser(\Jikan\Parser\Recommendations\RecommendationListItemParser $parser): self
    {
        $instance = new self();

        $instance->entry = $parser->getRecommendations();
        $instance->malId = $instance->getEntry()[0]->getMalId() . '-' . $instance->getEntry()[1]->getMalId();
        $instance->content = $parser->getContent();
        $instance->user = $parser->getRecommender();
        $instance->date = $parser->getDate();

        return $instance;
    }

    /**
     * @return string
     */
    public function getMalId(): string
    {
        return $this->malId;
    }

    /**
     * @return CommonMeta[]
     */
    public function getEntry(): array
    {
        return $this->entry;
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
    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return Recommender
     */
    public function getUser(): Recommender
    {
        return $this->user;
    }
}
