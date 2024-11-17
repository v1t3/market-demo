<?php

declare(strict_types=1);

namespace App\Utils\Manager;

use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 *
 */
final class ProductManager extends AbstractBaseManager
{
    /**
     * @var string
     */
    private string $productImagesDir;

    /**
     * @var ProductImageManager
     */
    private ProductImageManager $productImagesManager;

    /**
     * @param EntityManagerInterface $em
     * @param string                 $productImagesDir
     * @param ProductImageManager    $productImagesManager
     */
    public function __construct(
        EntityManagerInterface $em,
        string $productImagesDir,
        ProductImageManager $productImagesManager
    ) {
        parent::__construct($em);

        $this->productImagesDir = $productImagesDir;
        $this->productImagesManager = $productImagesManager;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository
    {
        return $this->em->getRepository(Product::class);
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()
                    ->createQueryBuilder('p');
    }

    /**
     * @param object $entity
     *
     * @return void
     */
    public function softRemove(object $entity): void
    {
        /** @var Product $product */
        $product = $entity;

        $this->persist($product);
        $product->setIsDeleted(true);
        $product->setIsPublished(false);
        $this->flush();
    }

    /**
     * @param Product $product
     *
     * @return string
     */
    public function getProductImagesDir(Product $product): string
    {
        return sprintf('%s/%s', $this->productImagesDir, $product->getId());
    }

    /**
     * @param Product     $product
     * @param string|null $tempImageFilename
     *
     * @return Product
     */
    public function updateProductImages(Product $product, ?string $tempImageFilename = null): Product
    {
        if (!$tempImageFilename) {
            return $product;
        }

        $productDir = $this->getProductImagesDir($product);

        /** @var ProductImage $productImages */
        $productImages = $this->productImagesManager->saveImageForProduct($productDir, $tempImageFilename);
        $productImages->setProduct($product);

        $product->addProductImage($productImages);

        return $product;
    }
}
