<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\SearchParse;

/**
 * Class Search
 *
 * @package Jikan\Get
 */
class Search extends Get
{

    private $validExtends = [];
    private $validNullQueries = [ANIME, MANGA];

    /**
     * Search constructor.
     *
     * @param null   $query
     * @param string $type
     * @param int    $page
     * @param null   $config
     *
     * @throws \Exception
     */
    public function __construct($query = null, $type = ANIME, $page = 1, $config = null)
    {

        if (!in_array($type, $this->validNullQueries) && is_null($query)) {
            throw new \Exception('No Query Given');
        }

        $this->query = is_null($query) ? '' : urlencode($query);

        $this->parser = new SearchParse;

        $link = BASE_URL;

        switch ($type) {
            case ANIME:
                $link .= 'anime.php?q='.$this->query.($page > 1 ? '&show='.($page - 1) * 50 : '').'&c[]=a&c[]=b&c[]=c&c[]=f';
                break;
            case MANGA:
                $link .= 'manga.php?q='.$this->query.($page > 1 ? '&show='.($page - 1) * 50 : '').'&c[]=a&c[]=b&c[]=c&c[]=f';
                break;
            case CHARACTER:
                $link .= 'character.php?q='.$this->query.($page > 1 ? '&show='.($page - 1) * 50 : '');
                break;
            case PERSON:
                $link .= 'people.php?q='.$this->query.($page > 1 ? '&show='.($page - 1) * 50 : '');
                break;

            default:
                throw new \Exception('Invalid Search Type');
                break;
        }

        if (!is_null($config)) {
            if ($type == ANIME || $type == MANGA) {
                $link .= '&'.$config->build();
            }
        }

        $this->parser->setPath($link);
        $this->parser->loadFile();

        $this->response['code'] = $this->parser->status;
        $this->response = array_merge($this->response, $this->parser->parse($type, $this->parser->status));
    }
}
