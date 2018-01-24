<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\SearchParse;

class Search extends Get
{

    private $validExtends = [];

    public function __construct($query = null, $type = ANIME, $page = 1) {


        if (is_null($query)) {
            throw new \Exception('No query given');
        }

        $this->query = $query;

        $this->parser = new SearchParse;


        $link = BASE_URL;


        switch ($type) {
            case ANIME:
                $link .= 'anime.php?q=' . $this->query . ($page > 1 ? '&show=' . ($page-1)*50 : '' ) . '&c[]=a&c[]=b&c[]=c&c[]=f';
                break;
            case MANGA:
                $link .= 'manga.php?q=' . $this->query . ($page > 1 ? '&show=' . ($page-1)*50 : '' ) . '&c[]=a&c[]=b&c[]=c&c[]=f';
                break;
            case CHARACTER:
                $link .= 'character.php?q=' . $this->query . ($page > 1 ? '&show=' . ($page-1)*50 : '' );
                break;
            case PERSON:
                $link .= 'people.php?q=' . $this->query . ($page > 1 ? '&show=' . ($page-1)*50 : '' );
                break;
            
            default:
                throw new \Exception('Unknown search type \"'.$type.'\"');
                break;
        }

        $this->parser->setPath($link);
        $this->parser->loadFile();

        $this->response = array_merge($this->response, $this->parser->parse($type));
    }

}