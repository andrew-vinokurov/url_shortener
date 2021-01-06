<?php

namespace App\Entity;

use App\Repository\ShortUrlStatsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShortUrlStatsRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class ShortUrlStats
{


    /**
     * Many stats have one url. This is the owning side.
     * @ORM\ManyToOne(targetEntity="ShortUrl", inversedBy="stats")
     * @ORM\JoinColumn(name="url_id", referencedColumnName="id")
     */
    private $url;



    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userAgent;

    /**
     * @ORM\Column(type="bigint")
     */
    private $clientIp;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?ShortUrl
    {
        return $this->url;
    }

    public function setUrl(ShortUrl $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getClientIp(): ?string
    {
        return $this->clientIp;
    }

    public function setClientIp(string $clientIp): self
    {
        $this->clientIp = $clientIp;

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
        $this->setCreatedAt((new DateTime()));
    }
}
