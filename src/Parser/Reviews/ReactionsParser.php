<?php
namespace Jikan\Parser\Reviews;

use Jikan\Helper\JString;
use Jikan\Model\Reviews\Reactions;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ReactionsParser
 *
 * @package Jikan\Parser
 */
class ReactionsParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

    /**
     * @var array
     */
    private array $reactions;

    /**
     * ReactionsParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
        $this->reactions = json_decode($this->getReactions(), true);
    }

    /**
     * @return Reactions
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Reactions
    {
        return Reactions::fromParser($this);
    }

    /**
     * @return string
     */
    private function getReactions(): string
    {
        $reactions = JString::cleanse($this->crawler->attr('data-reactions'));

        if (empty($reactions)) {
            $reactions = '{"icon":[],"num":0,"count":["0","0","0","0","0","0","0"]}';
        }

        return $reactions;
    }

    /**
     * @return int
     */
    public function getOverall(): int
    {
        return (int) $this->reactions['num'];
    }

    /**
     * @return int
     */
    public function getNice(): int
    {
        return (int) $this->reactions['count'][0];
    }

    /**
     * @return int
     */
    public function getLoveIt(): int
    {
        return (int) $this->reactions['count'][1];
    }

    /**
     * @return int
     */
    public function getFunny(): int
    {
        return (int) $this->reactions['count'][2];
    }

    /**
     * @return int
     */
    public function getConfusing(): int
    {
        return (int) $this->reactions['count'][3];
    }

    /**
     * @return int
     */
    public function getInformative(): int
    {
        return (int) $this->reactions['count'][4];
    }

    /**
     * @return int
     */
    public function getWellWritten(): int
    {
        return (int) $this->reactions['count'][5];
    }

    /**
     * @return int
     */
    public function getCreative(): int
    {
        return (int) $this->reactions['count'][6];
    }
}
