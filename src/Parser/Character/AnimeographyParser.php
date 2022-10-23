<?php

namespace Jikan\Parser\Character;

use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeographyParser
 *
 * @package Jikan\Parser
 */
class AnimeographyParser extends OgraphyParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * VoiceActingRoleParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
        parent::__construct($this->crawler);
    }

    /**
     * Return the model
     *
     * @return \Jikan\Model\Character\Animeography
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Character\Animeography
    {
        return Model\Character\Animeography::fromParser($this);
    }

    /**
     * @return \Jikan\Model\Common\AnimeMeta
     * @throws \InvalidArgumentException
     */
    public function getAnimeMeta(): Model\Common\AnimeMeta
    {
        return new Model\Common\AnimeMeta(
            $this->getName(),
            $this->getUrl(),
            $this->getImage()
        );
    }
}
