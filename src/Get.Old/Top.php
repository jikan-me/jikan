<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\TopParse;

class Top extends Get
{

    private $validExtends = [];
    const VALID_TYPES = [ANIME, MANGA];
    const VALID_SUBTYPES = [TOP_AIRING, TOP_UPCOMING, TOP_TV, TOP_MOVIE, TOP_OVA, TOP_SPECIAL, TOP_MANGA, TOP_NOVEL, TOP_ONE_SHOT, TOP_DOUJINSHI, TOP_MANHWA, TOP_MANHUA, TOP_POPULARITY, TOP_FAVORITE];

    public function __construct($type, $page = 1, $subtype = null) {

        if (!in_array($type, self::VALID_TYPES)) {
            throw new \Exception("Argument #1 Unsupported (Supported: ANIME, MANGA)");
        }

        if (!is_null($subtype)) {
            if (!in_array($subtype, self::VALID_SUBTYPES)) {
                throw new \Exception("Unsupported type. Refer to src/config.php for valid constants");
            }
        }

        $this->parser = new TopParse;

        $link = BASE_URL . 'top'.$type.'.php?limit=' . ($page-1)*50;

        if (!is_null($subtype)) {
            $link .= '&type=' . $subtype;
        }


        $this->parser->setPath($link);

        $this->parser->loadFile();

        $this->response['code'] = $this->parser->status;
        $this->response = array_merge($this->response, $this->parser->parse($type));
    }

}