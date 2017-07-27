<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\AnimeParse;
use Jikan\Lib\Parser\AnimeEpisodeParse;


class Anime extends Get
{

    private $validExtends = [CHARACTERS_STAFF, EPISODES];

	public function __construct($id = null, $extend = null) {


        if (is_null($id)) {
            throw new \Exception('ID/Path not given');
        }

        $this->id = $id;

        if (empty($extend)) {

            $this->parser = new AnimeParse;
            $this->parser->setPath(BASE_URL . ANIME_ENDPOINT . $this->id);
            $this->parser->loadFile();

            $this->response = $this->parser->parse();

	    } else {

            if (count(array_intersect($extend, $this->validExtends)) == 0) {
                throw new \Exception('Unsupported parse request');
            }

            $this->extend = $extend;
            foreach ($this->extend as $key => $extend) {
                $this->{$extend}();
            }

        }



        return $this;
	}

	public function episodes() {
	    $this->parser = new AnimeEpisodeParse;
        $this->parser->setPath(BASE_URL . ANIME_ENDPOINT . $this->id . '/_/episode');
        $this->parser->loadFile();

        $this->response = $this->parser->parse();

	    return $this;
	}

	public function charactersStaff() {
        echo "charactersStaff<br>";
	    return $this;
	}

}