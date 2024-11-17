<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductImageRepository;
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
    Table(name: '`product_image`'),
    Entity(repositoryClass: ProductImageRepository::class)
]
/**
 * @ApiResource(
 *     collectionOperations={
 *       "get"={
 *          "normalization_context"={"groups"="product_image:list"}
 *       },
 *     },
 *     itemOperations={
 *       "get"={
 *          "normalization_context"={"groups"="product_image:item"}
 *       },
 *     },
 * )
 */
class ProductImage
{
    /**
     * @var int|null
     */
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    #[Groups(['cart_product:list', 'cart_product:item', 'cart:list', 'cart:item'])]
    protected ?int $id;

    /**
     * @var Product|null
     */
    #[ManyToOne(targetEntity: Product::class, inversedBy: 'productImages'), JoinColumn(nullable: false)]
    protected ?Product $product;

    /**
     * @var string|null
     */
    #[Column(type: Types::STRING, length: 255)]
    protected ?string $filenameBig;

    /**
     * @var string|null
     */
    #[Column(type: Types::STRING, length: 255)]
    protected ?string $filenameMiddle;

    /**
     * @var string|null
     */
    #[Column(type: Types::STRING, length: 255)]
    #[Groups(['cart_product:list', 'cart_product:item', 'cart:list', 'cart:item'])]
    protected ?string $filenameSmall;

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
     * @return string|null
     */
    public function getFilenameBig(): ?string
    {
        return $this->filenameBig;
    }

    /**
     * @param string $filenameBig
     *
     * @return $this
     */
    public function setFilenameBig(string $filenameBig): static
    {
        $this->filenameBig = $filenameBig;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilenameMiddle(): ?string
    {
        return $this->filenameMiddle;
    }

    /**
     * @param string $filenameMiddle
     *
     * @return $this
     */
    public function setFilenameMiddle(string $filenameMiddle): static
    {
        $this->filenameMiddle = $filenameMiddle;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilenameSmall(): ?string
    {
        return $this->filenameSmall;
    }

    /**
     * @param string $filenameSmall
     *
     * @return $this
     */
    public function setFilenameSmall(string $filenameSmall): static
    {
        $this->filenameSmall = $filenameSmall;

        return $this;
    }
}
