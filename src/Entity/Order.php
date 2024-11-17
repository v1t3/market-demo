<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Repository\OrderRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
#[
    Table(name: '`order`'),
    Entity(repositoryClass: OrderRepository::class)
]
/**
 * @ApiResource(
 *     collectionOperations={
 *       "get"={
 *          "normalization_context"={"groups"="order:list"}
 *       },
 *       "post"={
 *          "security"="is_granted('ROLE_USER')",
 *          "normalization_context"={"groups"="order:list:write"}
 *       }
 *     },
 *     itemOperations={
 *       "get"={
 *          "normalization_context"={"groups"="order:item"}
 *       },
 *     },
 * )
 */
class Order
{
    /**
     * @var int|null
     */
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    #[Groups(['order:item'])]
    protected ?int $id;

    /**
     * @var DateTimeImmutable
     */
    #[Column(type: Types::DATETIME_IMMUTABLE)]
    protected DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable
     */
    #[Column(type: Types::DATETIME_IMMUTABLE)]
    protected DateTimeImmutable $updatedAt;

    /**
     * @var User|null
     */
    #[ManyToOne(targetEntity: User::class, inversedBy: 'orders'), JoinColumn(nullable: false)]
    protected ?User $owner;

    /**
     * @var int|null
     */
    #[Column(type: Types::INTEGER)]
    #[Groups(['order:item'])]
    protected ?int $status;

    /**
     * @var float|null
     */
    #[Column(type: Types::FLOAT, nullable: true)]
    #[Groups(['order:item'])]
    protected ?float $totalPrice;

    /**
     * @var bool
     */
    #[Column(type: Types::BOOLEAN)]
    protected bool $isDeleted;

    /**
     * @var Collection|ArrayCollection
     */
    #[OneToMany(mappedBy: 'appOrder', targetEntity: OrderProduct::class)]
    #[Groups(['order:item'])]
    protected Collection $orderProducts;

    /**
     *
     */
    public function __construct()
    {
        $this->id = null;
        $this->isDeleted = false;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->orderProducts = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return User|null
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * @param User|null $owner
     *
     * @return $this
     */
    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusName(): string
    {
        return null !== $this->status ? OrderStaticStorage::getOrderStatusChoices()[$this->status] : '';
    }

    /**
     * @return float|null
     */
    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    /**
     * @param float|null $totalPrice
     *
     * @return $this
     */
    public function setTotalPrice(?float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

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
     * @param DateTimeImmutable|null $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     *
     * @return $this
     */
    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    /**
     * @param OrderProduct $orderProduct
     *
     * @return $this
     */
    public function addOrderProduct(OrderProduct $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts[] = $orderProduct;
            $orderProduct->setAppOrder($this);
        }

        return $this;
    }

    /**
     * @param OrderProduct $orderProduct
     *
     * @return $this
     */
    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getAppOrder() === $this) {
                $orderProduct->setAppOrder(null);
            }
        }

        return $this;
    }
}
