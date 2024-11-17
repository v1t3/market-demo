<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var bool|null
     */
    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $code = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $preview = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $activeFrom = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $activeTo = null;

    /**
     * @var DateTimeImmutable|null
     */
    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    /**
     * @var DateTimeImmutable|null
     */
    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $previewImage = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detailImage = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool|null
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool|null $active
     *
     * @return $this
     */
    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPreview(): ?string
    {
        return $this->preview;
    }

    /**
     * @param string|null $preview
     *
     * @return $this
     */
    public function setPreview(?string $preview): static
    {
        $this->preview = $preview;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return $this
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getActiveFrom(): ?DateTimeInterface
    {
        return $this->activeFrom;
    }

    /**
     * @param DateTimeInterface|null $activeFrom
     *
     * @return $this
     */
    public function setActiveFrom(?DateTimeInterface $activeFrom): static
    {
        $this->activeFrom = $activeFrom;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getActiveTo(): ?DateTimeInterface
    {
        return $this->activeTo;
    }

    /**
     * @param DateTimeInterface|null $activeTo
     *
     * @return $this
     */
    public function setActiveTo(?DateTimeInterface $activeTo): static
    {
        $this->activeTo = $activeTo;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeImmutable $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPreviewImage(): ?string
    {
        return $this->previewImage;
    }

    /**
     * @param string|null $previewImage
     *
     * @return $this
     */
    public function setPreviewImage(?string $previewImage): static
    {
        $this->previewImage = $previewImage;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDetailImage(): ?string
    {
        return $this->detailImage;
    }

    /**
     * @param string|null $detailImage
     *
     * @return $this
     */
    public function setDetailImage(?string $detailImage): static
    {
        $this->detailImage = $detailImage;

        return $this;
    }
}
