<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     *
     */
    private const PAGE_SIZE = 10;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param int $offset
     *
     * @return Paginator
     */
    public function getPaginator(int $offset): Paginator
    {
        $query = $this->createQueryBuilder('a')
                      ->orderBy('a.id', 'DESC')
                      ->setMaxResults(self::PAGE_SIZE)
                      ->setFirstResult($offset)
                      ->getQuery();

        return new Paginator($query);
    }

    /**
     * @return int
     */
    public static function getPageSize(): int
    {
        return self::PAGE_SIZE;
    }
}
