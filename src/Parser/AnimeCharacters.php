<?php

namespace Jikan\Parser;

class AnimeCharacters extends \Skraypar\Skraypar
{
	public $model;

	public function __construct(&$model) {
		$this->model = &$model;
	}

    public function loadRules() {

    	/*
    	 * Characters & Voice Actors
    	 */
        $this->addRule('character', '~</div>Characters & Voice Actors</h2>~', function() {

            $characters = (new \Skraypar\Iterator(
                $this->file,
                $this->lineNo
            ))->setBreakpointPattern('~<a name="staff"></a>~');

            $characters->setIteratorCallable(function() use (&$characters) {

                $character = [
                    'mal_id' => null,
                    'url' => null,
                    'image_url' => null,
                    'name' => null,
                    'role' => null,
                    'voice_actor' => []
                ];

                if (preg_match('~<td valign="top" width="27" class="ac borderClass (bgColor2|bgColor1)">~', $characters->getLine())) {

                    $characters->lookAhead('~<img alt="(.*)" width="23" height="32" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', function() use (&$characters, &$character) {
                        $character['image_url'] = trim(substr(explode(",", $characters->matches[3])[1], 0, -3));
                    });

                    $characters->lookAhead('~<a href="(https://myanimelist.net/character/(.*)/(.*))">(.*)</a>~', function() use (&$characters, &$character) {
                        $character['mal_id'] = (int) $characters->matches[2];
                        $character['url'] = $characters->matches[1];
                        $character['name'] = $characters->matches[4];
                    });

                    $characters->lookAhead('~<small>(.*)</small>~', function() use (&$characters, &$character) {
                        $character['role'] = $characters->matches[1];
                    });

                    $voiceActors = new \Skraypar\Iterator($this->file, $this->lineNo);
                    $voiceActors->setBreakpointPattern('~</table>~');
                    $voiceActors->setIteratorCallable(function() use (&$voiceActors) {
                        $voiceActor = [
                            'mal_id' => null,
                            'url' => null,
                            'image_url' => null,
                            'name' => null,
                            'language' => null
                        ];

                        if (preg_match('~<td valign="top" align="right" style="padding: 0 4px;" nowrap="">~', $voiceActors->getLine())) {

                            $voiceActors->lookAhead('~<a href="(https://myanimelist.net/people/(.*)/(.*))">(.*)</a>~', function() use (&$voiceActors, &$voiceActor) {
                                $voiceActor['mal_id'] = (int) $voiceActors->matches[2];
                                $voiceActor['url'] = $voiceActors->matches[1];
                                $voiceActor['name'] = $voiceActors->matches[4];
                            });

                            $voiceActors->lookAhead('~<small>(.*)</small>~', function() use (&$voiceActors, &$voiceActor) {
                                $voiceActor['language'] = $voiceActors->matches[1];
                            });

                            $voiceActors->lookAhead('~<img alt="(.*)" width="23" height="32" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', function() use (&$voiceActors, &$voiceActor) {
                                $voiceActor['image_url'] = trim(substr(explode(",", $voiceActors->matches[3])[1], 0, -3));
                            });

                            $voiceActors->response[] = $voiceActor;
                        }

                    });

                    $voiceActors->parse();
                    $character['voice_actor'] = $voiceActors->response;

                    $characters->response[] = $character;
                }
            });

            $characters->parse();
            $this->model->set('AnimeCharacters', 'character', 
            	$characters->response
        	);
        });
        /*
         * Staff
         */
        $this->addRule('staff', '~<a name="staff"></a>~', function() {

            $staff = (new \Skraypar\Iterator(
                $this->file,
                $this->lineNo
            ))->setBreakpointPattern('~<div style="clear:both;"></div>~');

            $staff->setIteratorCallable(function() use (&$staff) {

                $person = [
                    'mal_id' => null,
                    'url' => null,
                    'image_url' => null,
                    'name' => null,
                    'role' => null
                ];

                if (preg_match('~<table border="0" cellpadding="0" cellspacing="0" width="100%">~', $staff->getLine())) {

                    $staff->lookAhead('~<img alt="(.*)" width="23" height="32" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', function() use (&$staff, &$person) {
                        $person['image_url'] = trim(substr(explode(",", $staff->matches[3])[1], 0, -3));
                    });

                    $staff->lookAhead('~<a href="(https://myanimelist.net/people/(.*)/(.*))">(.*)</a>~', function() use (&$staff, &$person) {
                        $person['mal_id'] = (int) $staff->matches[2];
                        $person['url'] = $staff->matches[1];
                        $person['name'] = $staff->matches[4];
                    });

                    $staff->lookAhead('~<small>(.*)</small>~', function() use (&$staff, &$person) {
                        $person['role'] = $staff->matches[1];
                    });

					$staff->response[] = $person;
                }
        	});

			$staff->parse();
			$this->model->set('AnimeCharacters', 'staff', 
				$staff->response
			);
		});
	}
}