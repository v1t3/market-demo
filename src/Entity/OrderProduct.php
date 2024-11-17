<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
#[
    Table(name: '`order_product`'),
    Entity(repositoryClass: OrderProductRepository::class)
]
/**
 * @ApiResource(
 *     collectionOperations={
 *       "get"={
 *          "normalization_context"={"groups"="order_product:list"}
 *       },
 *       "post"={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "normalization_context"={"groups"="order_product:list:write"}
 *       }
 *     },
 *     itemOperations={
 *       "get"={
 *          "normalization_context"={"groups"="order_product:item"}
 *       },
 *       "delete"={
 *          "security"="is_granted('ROLE_ADMIN')",
 *       },
 *     },
 * )
 */
class OrderProduct
{
    /**
     * @var int|null
     */
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    #[Groups(['order_product:list', 'order:item'])]
    protected ?int $id;

    /**
     * @var Order|null
     */
    #[ManyToOne(targetEntity: Order::class, cascade: ['persist'], inversedBy: 'orderProducts'), JoinColumn(nullable: false)]
    #[Groups(['order:item'])]
    protected ?Order $appOrder;

    /**
     * @var Product|null
     */
    #[ManyToOne(targetEntity: Product::class, inversedBy: 'orderProducts'), JoinColumn(nullable: false)]
    #[Groups(['order:item'])]
    protected ?Product $product;

    /**
     * @var int|null
     */
    #[Column(type: Types::INTEGER)]
    #[Groups(['order:item'])]
    protected ?int $quantity;

    /**
     * @var string|null
     */
    #[Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    #[Groups(['order:item'])]
    protected ?string $pricePerOne;

    /**
     *
     */
    public function __construct()
    {
        $this->id = null;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Order|null
     */
    public function getAppOrder(): ?Order
    {
        return $this->appOrder;
    }

    /**
     * @param Order|null $appOrder
     *
     * @return $this
     */
    public function setAppOrder(?Order $appOrder): static
    {
        $this->appOrder = $appOrder;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     *
     * @return $this
     */
    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return $this
     */
    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPricePerOne(): ?string
    {
        return $this->pricePerOne;
    }

    /**
     * @param string|null $pricePerOne
     *
     * @return $this
     */
    public function setPricePerOne(?string $pricePerOne): static
    {
        $this->pricePerOne = $pricePerOne;

        return $this;
    }
}
