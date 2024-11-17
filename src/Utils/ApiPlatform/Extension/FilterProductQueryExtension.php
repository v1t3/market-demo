<?php

/** @noinspection PhpUndefinedClassInspection */

declare(strict_types=1);

namespace App\Utils\ApiPlatform\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
// region see vendor/api-platform/core/src/Core/Bridge/Doctrine/Orm/Extension/QueryItemExtensionInterface.php
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface as LegacyQueryNameGeneratorInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
// endregion
use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;

/**
 *
 */
class FilterProductQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /**
     * @param QueryBuilder                                                  $queryBuilder
     * @param QueryNameGeneratorInterface|LegacyQueryNameGeneratorInterface $queryNameGenerator
     * @param string                                                        $resourceClass
     * @param string|null                                                   $operationName
     *
     * @return void
     */
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface|LegacyQueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?string $operationName = null
    ): void {
        $this->andWhere($queryBuilder, $resourceClass);
    }

    /**
     * @param QueryBuilder                                                  $queryBuilder
     * @param QueryNameGeneratorInterface|LegacyQueryNameGeneratorInterface $queryNameGenerator
     * @param string                                                        $resourceClass
     * @param array                                                         $identifiers
     * @param string|null                                                   $operationName
     * @param array                                                         $context
     *
     * @return void
     */
    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface|LegacyQueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        ?string $operationName = null,
        array $context = []
    ): void {
        $this->andWhere($queryBuilder, $resourceClass);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $resourceClass
     *
     * @return void
     */
    private function andWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (Product::class !== $resourceClass) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->andWhere(
            sprintf("%s.isDeleted='0'", $rootAlias)
        );
    }
}
