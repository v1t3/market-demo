<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return QueryBuilder
     */
    public function findActiveProduct(): QueryBuilder
    {
        return $this
            ->createQueryBuilder('p')
            ->andWhere('p.isDeleted = false')
            ->andWhere('p.isPublished = true')
            ->orderBy('p.id', 'DESC');
    }

    /**
     * @param int|null $categoryId
     * @param int|null $productCount
     *
     * @return array
     */
    public function findByCategoryAndCount(?int $categoryId, ?int $productCount = null): array
    {
        $queryBuilder = $this->findActiveProduct();

        if ($categoryId) {
            $queryBuilder
                ->andWhere('p.category = :idCategory')
                ->setParameter('idCategory', $categoryId);
        }

        if ($productCount) {
            $queryBuilder->setMaxResults($productCount);
        }

        return $this->getResult($queryBuilder);
    }

    /**
     * @param string $productId
     *
     * @return Product|null
     */
    public function findById(string $productId): ?Product
    {
        $queryBuilder = $this->findActiveProduct();

        $queryBuilder
            ->andWhere('p.uuid=:uuid')
            ->setParameter('uuid', $productId);

        try {
            $queryBuilder = $this->getSingleResult($queryBuilder);
        } catch (NoResultException|NonUniqueResultException) {
            return null;
        }

        return $queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return array
     */
    private function getResult(QueryBuilder $queryBuilder): array
    {
        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    private function getSingleResult(QueryBuilder $queryBuilder): Product
    {
        return $queryBuilder
            ->getQuery()
            ->getSingleResult();
    }
}
