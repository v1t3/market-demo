<?php

declare(strict_types=1);

namespace App\Utils\Manager;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 *
 */
final class ArticleManager extends AbstractBaseManager
{
    /**
     * @var string
     */
    private string $articleImagesDir;

    /**
     * @param EntityManagerInterface $em
     * @param string                 $articleImagesDir
     */
    public function __construct(EntityManagerInterface $em, string $articleImagesDir)
    {
        parent::__construct($em);
        $this->articleImagesDir = $articleImagesDir;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository
    {
        return $this->em->getRepository(Article::class);
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->getRepository()
                    ->createQueryBuilder('a')
                    ->orderBy('a.id', 'desc');
    }

    /**
     * @param Article $article
     *
     * @return string
     */
    public function getArticleImagesDir(Article $article): string
    {
        return sprintf('%s/%s', $this->articleImagesDir, $article->getId());
    }
}
