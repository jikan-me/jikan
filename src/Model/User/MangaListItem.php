<?php

namespace Jikan\Model\User;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MagazineMeta;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;

/**
 * Class MangaListItem
 *
 * @package Jikan\Model
 */
class MangaListItem
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
    private $readingStatus;

    /**
     * @var float
     */
    private $score;

    /**
     * @var int
     */
    private $readChapters;

    /**
     * @var int
     */
    private $readVolumes;

    /**
     * @var int
     */
    private $totalChapters;

    /**
     * @var int
     */
    private $totalVolumes;

    /**
     * @var int
     */
    private $publishingStatus;

    /**
     * @var bool
     */
    private $isRereading;

    /**
     * @var string|null
     */
    private $tags;

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
    private $readStartDate;

    /**
     * @var \DateTimeImmutable|null
     */
    private $readEndDate;

    /**
     * @var string|null
     */
    private $days;

    /**
     * @var string|null
     */
    private $retail;

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
    private $magazines = [];

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
     * @return MangaListItem
     */
    public static function factory(\stdClass $item): self
    {
        $instance = new self();

        $instance->malId = $item->manga_id;
        $instance->title = $item->manga_title;
        $instance->images = CommonImageResource::factory(
            Parser::parseImageQuality($item->manga_image_path)
        );
        $instance->url = Constants::BASE_URL . $item->manga_url;
        $instance->readingStatus = $item->status;
        $instance->score = $item->score;
        $instance->tags = empty($item->tags) ? null : $item->tags;
        $instance->isRereading = (bool) $item->is_rereading;
        $instance->readChapters = $item->num_read_chapters;
        $instance->readVolumes = $item->num_read_volumes;
        $instance->totalChapters = $item->manga_num_chapters;
        $instance->totalVolumes = $item->manga_num_volumes;
        $instance->publishingStatus = $item->manga_publishing_status;
        $instance->type = $item->manga_media_type_string;
        $instance->startDate = Parser::parseDateMDY($item->manga_start_date_string);
        $instance->endDate = Parser::parseDateMDY($item->manga_end_date_string);
        $instance->readStartDate = Parser::parseDateMDY($item->start_date_string);
        $instance->readEndDate = Parser::parseDateMDY($item->finish_date_string);
        $instance->days = $item->days_string;
        $instance->retail= empty($item->retail_string) ? null : $item->retail_string;
        $instance->priority = $item->priority_string;
        $instance->addedToList = $item->is_added_to_list;

        if ($item->manga_magazines !== null) {
            foreach ($item->manga_magazines as $magazine) {
                $magazineNameCanonical = JString::strToCanonical($magazine->name);

                $instance->magazines[] = new MalUrl(
                    $magazine->name,
                    Constants::BASE_URL . "/manga/magazine/{$magazine->id}/{$magazineNameCanonical}"
                );
            }
        }

        if ($item->genres !== null && !empty($item->genres)) {
            foreach ($item->genres as $genre) {
                $instance->genres[] = new MalUrl(
                    $genre->name,
                    Constants::BASE_URL . "/manga/genre/{$genre->id}/{$genre->name}"
                );
            }
        }

        if ($item->demographics !== null && !empty($item->demographics)) {
            foreach ($item->demographics as $demographic) {
                $instance->demographics[] = new MalUrl(
                    $demographic->name,
                    Constants::BASE_URL . "/manga/genre/{$demographic->id}/{$demographic->name}"
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getReadingStatus(): int
    {
        return $this->readingStatus;
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
    public function getReadChapters(): int
    {
        return $this->readChapters;
    }

    /**
     * @return int
     */
    public function getReadVolumes(): int
    {
        return $this->readVolumes;
    }

    /**
     * @return int
     */
    public function getTotalChapters(): int
    {
        return $this->totalChapters;
    }

    /**
     * @return int
     */
    public function getTotalVolumes(): int
    {
        return $this->totalVolumes;
    }

    /**
     * @return int
     */
    public function getPublishingStatus(): int
    {
        return $this->publishingStatus;
    }

    /**
     * @return bool
     */
    public function isRereading(): bool
    {
        return $this->isRereading;
    }

    /**
     * @return string|null
     */
    public function getTags(): ?string
    {
        return $this->tags;
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
    public function getReadStartDate(): ?\DateTimeImmutable
    {
        return $this->readStartDate;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getReadEndDate(): ?\DateTimeImmutable
    {
        return $this->readEndDate;
    }

    /**
     * @return string|null
     */
    public function getDays(): ?string
    {
        return $this->days;
    }

    /**
     * @return string|null
     */
    public function getRetail(): ?string
    {
        return $this->retail;
    }

    /**
     * @return string
     */
    public function getPriority(): string
    {
        return $this->priority;
    }

    /**
     * @return bool
     */
    public function isAddedToList(): bool
    {
        return $this->addedToList;
    }

    /**
     * @return array
     */
    public function getMagazines(): array
    {
        return $this->magazines;
    }
}
