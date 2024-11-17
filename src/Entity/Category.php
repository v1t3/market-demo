<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
#[
    Table(name: '`category`'),
    Entity(repositoryClass: CategoryRepository::class)
]
/**
 * @ApiResource(
 *     collectionOperations={
 *       "get"={
 *          "normalization_context"={"groups"="category:list"}
 *       },
 *     },
 *     itemOperations={
 *        "get"={
 *           "normalization_context"={"groups"="category:item"}
 *       },
 *     }
 * )
 */
class Category
{
    /**
     * @var int|null
     */
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    #[Groups(['category:list', 'category:item', 'product:list', 'product:item', 'order:item'])]
    protected ?int $id;

    /**
     * @var string|null
     */
    #[Column(type: Types::STRING, length: 100, nullable: true)]
    #[Groups(['category:list', 'category:item', 'product:list', 'product:item', 'order:item'])]
    protected ?string $title;

    /**
     * @var string|null
     */
    #[Slug(fields: ['title'])]
    #[Column(type: Types::STRING, length: 120, unique: true)]
    protected ?string $slug;

    /**
     * @var Collection|ArrayCollection
     */
    #[OneToMany(mappedBy: 'category', targetEntity: Product::class)]
    protected Collection $products;

    /**
     * @var bool
     */
    #[Column(type: Types::BOOLEAN, nullable: true)]
    protected bool $isDeleted;

    /**
     *
     */
    public function __construct()
    {
        $this->id = null;
        $this->isDeleted = false;
        $this->products = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return $this
     */
    public function setTitle(?string $title): static
    {
        // $this->title = $title;
        $this->title = ucfirst(strtolower($title));

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

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
     * @param bool|null $isDeleted
     *
     * @return $this
     */
    public function setIsDeleted(?bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}
