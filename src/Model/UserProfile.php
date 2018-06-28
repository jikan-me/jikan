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
     * @var string
     */    
    private $animeListUrl;

    /**
     * @var string
     */
    private $mangaListUrl;

    /**
     * @var int
     */
    private $mangaDaysRead;

    /**
     * @var int
     */
    private $mangaMeanScore;

    /**
     * @var int
     */
    private $mangaReading;

    /**
     * @var int
     */
    private $mangaCompleted;

    /**
     * @var int
     */
    private $mangaOnHold;

    /**
     * @var int
     */
    private $mangaDropped;

    /**
     * @var int
     */
    private $mangaPlanToRead;

    /**
     * @var int
     */
    private $mangaTotalEntries;

    /**
     * @var int
     */
    private $mangaReread;

    /**
     * @var int
     */
    private $mangaChaptersRead;

    /**
     * @var int
     */
    private $mangaVolumesRead;

    /**
     * @var array
     */
    private $favorite;

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
     * @return string
     */    
    public function getAnimeListUrl() : string {
        return $this->animeListUrl;
    } 

    /**
     * @return string
     */
    public function getMangaListUrl() : string {
        return $this->mangaListUrl;
    }

    /**
     * @return int
     */
    public function getMangaDaysRead() : int {
        return $this->mangaDaysRead;
    } 

    /**
     * @return int
     */
    public function getMangaMeanScore() : int {
        return $this->mangaMeanScore;
    } 

    /**
     * @return int
     */
    public function getMangaReading() : int {
        return $this->mangaReading;
    } 

    /**
     * @return int
     */
    public function getMangaCompleted() : int {
        return $this->mangaCompleted;
    } 

    /**
     * @return int
     */
    public function getMangaOnHold() : int {
        return $this->mangaOnHold;
    } 

    /**
     * @return int
     */
    public function getMangaDropped() : int {
        return $this->mangaDropped;
    } 

    /**
     * @return int
     */
    public function getMangaPlanToRead() : int {
        return $this->mangaPlanToRead;
    } 

    /**
     * @return int
     */
    public function getMangaTotalEntries() : int {
        return $this->mangaTotalEntries;
    } 

    /**
     * @return int
     */
    public function getMangaReread() : int {
        return $this->mangaReread;
    } 

    /**
     * @return int
     */
    public function getMangaChaptersRead() : int {
        return $this->mangaChaptersRead;
    } 

    /**
     * @return int
     */
    public function getMangaVolumesRead() : int {
        return $this->mangaVolumesRead;
    } 

    /**
     * @return array
     */
    public function getFavorites() : array {
        return $this->favorite;
    } 
}
