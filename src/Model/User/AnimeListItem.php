<?php

namespace Jikan\Model\User;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\LicensorMeta;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\StudioMeta;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;

/**
 * Class AnimeListItem
 *
 * @package Jikan\Model
 */
class AnimeListItem
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $videoUrl;

    /**
     * @var string
     */
    private $url;

    /**
     * @var CommonImageResource
     */
    private $images;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $watchingStatus;

    /**
     * @var float
     */
    private $score;

    /**
     * @var int
     */
    private $watchedEpisodes;

    /**
     * @var int
     */
    private $totalEpisodes;

    /**
     * @var int
     */
    private $airingStatus;

    /**
     * @var string|null
     */
    private $seasonName;

    /**
     * @var int|null
     */
    private $seasonYear;

    /**
     * @var bool
     */
    private $hasEpisodeVideo;

    /**
     * @var bool
     */
    private $hasPromoVideo;

    /**
     * @var bool
     */
    private $hasVideo;

    /**
     * @var bool
     */
    private $isRewatching;

    /**
     * @var string|null
     */
    private $tags;

    /**
     * @var string|null
     */
    private $rating;

    /**
     * @var \DateTimeImmutable|null
     */
    private $startDate;

    /**
     * @var \DateTimeImmutable|null
     */
    private $endDate;

    /**
     * @var \DateTimeImmutable|null
     */
    private $watchStartDate;

    /**
     * @var \DateTimeImmutable|null
     */
    private $watchEndDate;

    /**
     * @var string|null
     */
    private $days;

    /**
     * @var string|null
     */
    private $storage;

    /**
     * @var string
     */
    private $priority;

    /**
     * @var bool
     */
    private $addedToList;

    /**
     * @var array
     */
    private $studios = [];

    /**
     * @var array
     */
    private $licensors = [];

    /**
     * @var array|MalUrl
     */
    private $genres = [];

    /**
     * @var array|MalUrl
     */
    private $demographics = [];

    /**
     * @param  \stdClass $item
     * @return AnimeListItem
     */
    public static function factory(\stdClass $item): self
    {
        $instance = new self();

        $instance->malId = $item->anime_id;
        $instance->title = $item->anime_title;
        $instance->images = CommonImageResource::factory(
            Parser::parseImageQuality($item->anime_image_path)
        );
        $instance->url = Constants::BASE_URL . $item->anime_url;
        $instance->videoUrl = Constants::BASE_URL . $item->video_url;
        $instance->watchingStatus = $item->status;
        $instance->score = $item->score;
        $instance->tags = empty($item->tags) ? null : $item->tags;
        $instance->isRewatching = (bool) $item->is_rewatching;
        $instance->watchedEpisodes = $item->num_watched_episodes;
        $instance->totalEpisodes = $item->anime_num_episodes;
        $instance->airingStatus = $item->anime_airing_status;
        $instance->hasEpisodeVideo = $item->has_episode_video;
        $instance->hasPromoVideo = $item->has_promotion_video;
        $instance->hasVideo = $item->has_video;
        $instance->type = $item->anime_media_type_string;
        $instance->rating = $item->anime_mpaa_rating_string;
        $instance->startDate = Parser::parseDateDMY($item->anime_start_date_string);
        $instance->endDate = Parser::parseDateDMY($item->anime_end_date_string);
        $instance->watchStartDate = Parser::parseDateDMY($item->start_date_string);
        $instance->watchEndDate = Parser::parseDateDMY($item->finish_date_string);
        $instance->days = $item->days_string;
        $instance->storage = empty($item->storage_string) ? null : $item->storage_string;
        $instance->priority = $item->priority_string;
        $instance->addedToList = $item->is_added_to_list;

        if (isset($item->anime_season->season)) {
            $instance->seasonName = $item->anime_season->season;
            $instance->seasonYear = $item->anime_season->year;
        }

        if ($item->anime_studios !== null) {
            foreach ($item->anime_studios as $studio) {
                $studioNameCanonical = JString::strToCanonical($studio->name);

                $instance->studios[] = new MalUrl(
                    $studio->name,
                    Constants::BASE_URL . "/anime/producer/{$studio->id}/{$studioNameCanonical}"
                );
            }
        }

        if ($item->anime_licensors !== null) {
            foreach ($item->anime_licensors as $licensor) {
                $licensorNameCanonical = JString::strToCanonical($licensor->name);

                $instance->licensors[] = new MalUrl(
                    $licensor->name,
                    Constants::BASE_URL . "/anime/producer/{$licensor->id}/{$licensorNameCanonical}"
                );
            }
        }

        if ($item->genres !== null && !empty($item->genres)) {
            foreach ($item->genres as $genre) {
                $instance->genres[] = new MalUrl(
                    $genre->name,
                    Constants::BASE_URL . "/anime/genre/{$genre->id}/{$genre->name}"
                );
            }
        }

        if ($item->demographics !== null && !empty($item->demographics)) {
            foreach ($item->demographics as $demographic) {
                $instance->demographics[] = new MalUrl(
                    $demographic->name,
                    Constants::BASE_URL . "/anime/genre/{$demographic->id}/{$demographic->name}"
                );
            }
        }

        return $instance;
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array|MalUrl
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * @return array|MalUrl
     */
    public function getDemographics()
    {
        return $this->demographics;
    }

    /**
     * @return string
     */
    public function getVideoUrl(): string
    {
        return $this->videoUrl;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return CommonImageResource
     */
    public function getImages(): CommonImageResource
    {
        return $this->images;
    }

    /**
     * @return bool
     */
    public function isAddedToList(): bool
    {
        return $this->addedToList;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getWatchingStatus(): int
    {
        return $this->watchingStatus;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @return int
     */
    public function getWatchedEpisodes(): int
    {
        return $this->watchedEpisodes;
    }

    /**
     * @return int
     */
    public function getTotalEpisodes(): int
    {
        return $this->totalEpisodes;
    }

    /**
     * @return int
     */
    public function getAiringStatus(): int
    {
        return $this->airingStatus;
    }

    /**
     * @return string|null
     */
    public function getSeasonName(): ?string
    {
        return $this->seasonName;
    }

    /**
     * @return int|null
     */
    public function getSeasonYear(): ?int
    {
        return $this->seasonYear;
    }

    /**
     * @return bool
     */
    public function isHasEpisodeVideo(): bool
    {
        return $this->hasEpisodeVideo;
    }

    /**
     * @return bool
     */
    public function isHasPromoVideo(): bool
    {
        return $this->hasPromoVideo;
    }

    /**
     * @return bool
     */
    public function isHasVideo(): bool
    {
        return $this->hasVideo;
    }

    /**
     * @return bool
     */
    public function isRewatching(): bool
    {
        return $this->isRewatching;
    }

    /**
     * @return null|string
     */
    public function getTags(): ?string
    {
        return $this->tags;
    }

    /**
     * @return null|string
     */
    public function getRating(): ?string
    {
        return $this->rating;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getWatchStartDate(): ?\DateTimeImmutable
    {
        return $this->watchStartDate;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getWatchEndDate(): ?\DateTimeImmutable
    {
        return $this->watchEndDate;
    }

    /**
     * @return null|string
     */
    public function getDays(): ?string
    {
        return $this->days;
    }

    /**
     * @return null|string
     */
    public function getStorage(): ?string
    {
        return $this->storage;
    }

    /**
     * @return string
     */
    public function getPriority(): string
    {
        return $this->priority;
    }

    /**
     * @return array
     */
    public function getStudios(): array
    {
        return $this->studios;
    }

    /**
     * @return array
     */
    public function getLicensors(): array
    {
        return $this->licensors;
    }
}
