<?php

declare(strict_types=1);

namespace App\Form\DTO;

use App\Entity\Category;
use App\Entity\Product;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 *
 */
class EditProductModel
{
    /**
     * @param int|null               $id
     * @param string|null            $title
     * @param string|null            $price
     * @param UploadedFile|null      $newImage
     * @param int|null               $quantity
     * @param string|null            $description
     * @param Category|null          $category
     * @param bool|null              $isPublished
     * @param bool|null              $isDeleted
     * @param DateTimeImmutable|null $createdAt
     */
    public function __construct(
        public ?int $id = null,
        public ?string $title = null,
        public ?string $price = null,
        public ?UploadedFile $newImage = null,
        public ?int $quantity = null,
        public ?string $description = null,
        public ?Category $category = null,
        public ?bool $isPublished = null,
        public ?bool $isDeleted = null,
        public ?DateTimeImmutable $createdAt = null
    ) {
    }

    /**
     * @param Product|null $product
     *
     * @return self
     */
    public static function makeFromProduct(?Product $product = null): self
    {
        $model = new self();

        if (!$product) {
            return $model;
        }

        $model->id = $product->getId();
        $model->title = $product->getTitle();
        $model->price = $product->getPrice();
        $model->quantity = $product->getQuantity();
        $model->description = $product->getDescription();
        $model->category = $product->getCategory();
        $model->isPublished = $product->getIsPublished();
        $model->isDeleted = $product->getIsDeleted();
        $model->createdAt = $product->getCreatedAt();

        return $model;
    }
}
