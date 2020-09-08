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
class MangaographyParser extends OgraphyParser implements ParserInterface
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
     * @return \Jikan\Model\Character\Mangaography
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Character\Mangaography
    {
        return Model\Character\Mangaography::fromParser($this);
    }

    /**
     * @return \Jikan\Model\Common\MangaMeta
     * @throws \InvalidArgumentException
     */
    public function getMangaMeta(): Model\Common\MangaMeta
    {
        return new Model\Common\MangaMeta(
            $this->getName(),
            $this->getUrl(),
            $this->getImage()
        );
    }
}
