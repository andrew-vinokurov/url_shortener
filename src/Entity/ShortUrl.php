<?php

namespace App\Entity;

use App\Repository\ShortUrlRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShortUrlRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class ShortUrl
{
    /**
     * One url has many stats. This is the inverse side.
     * @ORM\OneToMany(targetEntity="ShortUrlStats", mappedBy="url")
     */
    private $stats;

    public function __construct() {
        $this->stats = new ArrayCollection();
    }

    /**
     * @return Collection|ShortUrlStats[]
     */
    public function getStats(): Collection
    {
        return $this->stats;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=32, unique=true, options={"fixed" = true})
     */
    private $hashUrl;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expireAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getHashUrl(): ?string
    {
        return $this->hashUrl;
    }

    public function setHashUrl(string $hashUrl): self
    {
        $this->hashUrl = $hashUrl;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getExpireAt(): ?DateTime
    {
        return $this->expireAt;
    }

    public function setExpireAt(DateTime $expireAt): self
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAndUpdatedTime()
    {
        $this->setUpdatedAt((new DateTime()));
        $this->setCreatedAt((new DateTime()));
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateModificationDate()
    {
        $this->setCreatedAt((new DateTime()));
    }
}
