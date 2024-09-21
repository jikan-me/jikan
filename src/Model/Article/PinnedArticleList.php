<?php

namespace Jikan\Model\Article;

use Jikan\Model\Common\Collection\Results;
use Jikan\Parser;

/**
 * Class PinnedArticleList
 */
class PinnedArticleList extends Results
{
    /**
     * @param Parser\Article\PinnedArticleListParser $parser
     * @return self
     */
    public static function fromParser(Parser\Article\PinnedArticleListParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getResults();

        return $instance;
    }

    /**
     * @return static
     */
    public static function mock(): self
    {
        return new self();
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
