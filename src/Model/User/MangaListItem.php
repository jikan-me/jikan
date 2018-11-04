<?php

namespace Jikan\Model\User;

use Jikan\Helper\Constants;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MagazineMeta;

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
     * @var string
     */
    private $imageUrl;

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
     * @param \stdClass $parser
     *
     * @return AnimeListItem
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public static function factory(\stdClass $item): self
    {
        $instance = new self();

        $instance->malId = $item->manga_id;
        $instance->title = $item->manga_title;
        $instance->imageUrl = Parser::parseImageQuality($item->manga_image_path);
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
        $instance->startDate = Parser::parseDateDMY($item->manga_start_date_string);
        $instance->endDate = Parser::parseDateDMY($item->manga_end_date_string);
        $instance->readStartDate = Parser::parseDateDMY($item->start_date_string);
        $instance->readEndDate = Parser::parseDateDMY($item->finish_date_string);
        $instance->days = $item->days_string;
        $instance->retail= empty($item->retail_string) ? null : $item->retail_string;
        $instance->priority = $item->priority_string;
        $instance->addedToList = $item->is_added_to_list;

        if (!is_null($item->manga_magazines)) {
            foreach ($item->manga_magazines as $magazine) {
                $instance->magazines[] = new MagazineMeta($magazine->id, $magazine->name);
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
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
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
     * @return null|string
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
     * @return null|string
     */
    public function getDays(): ?string
    {
        return $this->days;
    }

    /**
     * @return null|string
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
