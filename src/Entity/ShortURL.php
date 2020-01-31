<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShortURLRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ShortURL {

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="additional_id", type="string", unique=true)
     */
    protected $additionalID;

    /**
     * @var string|null
     * @ORM\Column(name="source_name", type="string", unique=true)
     */
    protected $sourceName;

    /**
     * @var string|null
     * @ORM\Column(name="destination_url", type="text")
     */
    protected $destinationURL;

    /**
     * @var DateTime|null
     * @ORM\Column(name="valid_since", type="datetime", nullable=true)
     */
    protected $validSince;

    /**
     * @var DateTime|null
     * @ORM\Column(name="valid_until", type="datetime", nullable=true)
     */
    protected $validUntil;

    /**
     * @var bool
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active;

    /**
     * @var DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var DateTime
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * ShortURL constructor.
     */
    public function __construct() {
        $this->additionalID = uniqid();
        $this->active = true;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAdditionalID(): string {
        return $this->additionalID;
    }

    /**
     * @param string $additionalID
     */
    public function setAdditionalID(string $additionalID): void {
        $this->additionalID = $additionalID;
    }

    /**
     * @return string|null
     */
    public function getSourceName(): ?string {
        return $this->sourceName;
    }

    /**
     * @param string $sourceName
     */
    public function setSourceName(string $sourceName): void {
        $this->sourceName = $sourceName;
    }

    /**
     * @return string|null
     */
    public function getDestinationURL(): ?string {
        return $this->destinationURL;
    }

    /**
     * @param string $destinationURL
     */
    public function setDestinationURL(string $destinationURL): void {
        $this->destinationURL = $destinationURL;
    }

    /**
     * @return DateTime|null
     */
    public function getValidSince(): ?DateTime {
        return $this->validSince;
    }

    /**
     * @param DateTime|null $validSince
     */
    public function setValidSince(?DateTime $validSince): void {
        $this->validSince = $validSince;
    }

    /**
     * @return DateTime|null
     */
    public function getValidUntil(): ?DateTime {
        return $this->validUntil;
    }

    /**
     * @param DateTime|null $validUntil
     */
    public function setValidUntil(?DateTime $validUntil): void {
        $this->validUntil = $validUntil;
    }

    /**
     * @return bool
     */
    public function isActive(): bool {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void {
        $this->active = $active;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void {
        $this->updatedAt = new DateTime();
    }
}
