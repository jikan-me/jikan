<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class UserProfile
 *
 * @package Jikan\Model
 */
class UserProfile
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $image_url;

    /**
     * @var string
     */
    private $lastOnline;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var string
     */
    private $birthday;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */ 
    private $joined;

    /**
     * @var \Jikan\Model\AnimeStats
     */
    private $animeStats;

    /**
     * @var \Jikan\Model\MangaStats
     */
    private $mangaStats;

    /**
     * @var array
     */
    private $favorite;

    /**
     * @var string
     */
    private $about;

    /**
     * @param Parser\UserProfile $parser
     *
     * @return UserProfile
     */
    public static function fromParser(Parser\UserProfile $parser): self
    {
        $instance = new self();
        $instance->name = $parser->getUsername();
        $instance->url = $parser->getProfileUrl();
        $instance->image_url = $parser->getImageUrl();
        $instance->joined = $parser->getJoinDate();
        $instance->lastOnline = $parser->getLastOnline();
        $instance->gender = $parser->getGender();
        $instance->birthday = $parser->getBirthday();
        $instance->location = $parser->getLocation();
        $instance->animeStats = $parser->getAnimeStats();
        $instance->mangaStats = $parser->getMangaStats();
        $instance->about = $parser->getAbout();

        return $instance;
    }

    /**
     * @return string
     */
    public function getName() : string {
        return $this->name;
    } 

    /**
     * @return string
     */
    public function getLastOnline() : string {
        return $this->lastOnline;
    } 

    /**
     * @return string
     */
    public function getGender() : string {
        return $this->gender;
    } 

    /**
     * @return string
     */
    public function getBirthday() : string {
        return $this->birthday;
    } 

    /**
     * @return string
     */
    public function getLocation() : string {
        return $this->location;
    } 

    /**
     * @return string
     */ 
    public function getJoined() : string {
        return $this->joined;
    } 

    /**
     * @return \Jikan\Model\AnimeStats
     */
    public function getAnimeStats() : \Jikan\Model\AnimeStats {
        return $this->animeStats;
    }

    /**
     * @return \Jikan\Model\MangaStats
     */
    public function getMangaStats() : \Jikan\Model\MangaStats {
        return $this->mangaStats;
    }

    /**
     * @return array
     */
    public function getFavorites() : array {
        return $this->favorite;
    } 

    /**
     * @return string
     */
    public function getAbout() : string {
        return $this->about;
    } 
}
