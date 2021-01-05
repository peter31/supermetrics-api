<?php declare(strict_types=1);

namespace App\Entity;

class PostStatistics
{
    /** @var int */
    private $id;

    /** @var string */
    private $originalId;

    /** @var int */
    private $messageLength;

    /** @var string */
    private $fromName;

    /** @var string */
    private $fromId;

    /** @var \DateTime */
    private $createdTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalId(): ?string
    {
        return $this->originalId;
    }

    public function setOriginalId(?string $originalId)
    {
        $this->originalId = $originalId;
        return $this;
    }

    public function getMessageLength(): ?int
    {
        return $this->messageLength;
    }

    public function setMessageLength(?int $messageLength): self
    {
        $this->messageLength = $messageLength;
        return $this;
    }

    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    public function setFromName(?string $fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }

    public function getFromId(): ?string
    {
        return $this->fromId;
    }

    public function setFromId(?string $fromId)
    {
        $this->fromId = $fromId;
        return $this;
    }

    public function getCreatedTime(): ?\DateTime
    {
        return $this->createdTime;
    }

    public function setCreatedTime(?\DateTime $createdTime)
    {
        $this->createdTime = $createdTime;
        return $this;
    }
}