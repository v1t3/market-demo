<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry as Doctrine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 *
 */
class RobotsTxtController extends AbstractController
{
    /**
     * @var Doctrine
     */
    private Doctrine $doctrine;

    /**
     * @param Doctrine $doctrine
     *
     * @return $this
     */
    #[Required]
    public function setDoctrine(Doctrine $doctrine): RobotsTxtController
    {
        $this->doctrine = $doctrine;

        return $this;
    }

    /**
     * @return Response
     */
    #[Route('/robots.txt', name: 'main_robots.txt')]
    public function index(): Response
    {
        return $this->render('front/robots.txt.twig', [
            'activeCategories' => $this->getActiveCategories(),
            'activeProducts'   => $this->getActiveProducts(),
            'sitemap'          => $this->generateUrl(
                'main_sitemap',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ]);
    }

    /**
     * @return array
     */
    private function getActiveCategories(): array
    {
        $activeCategory = [];
        $categories = $this->doctrine
            ->getRepository(Category::class)
            ->findBy(['isDeleted' => false]);

        foreach ($categories as $category) {
            if ($category->getProducts()->count() > 0) {
                $preparingLink = explode(
                    '/',
                    $this->generateUrl('main_category_show', ['slug' => $category->getSlug()])
                );
                $preparingLink[1] = '*';
                $preparingLink = implode('/', $preparingLink);

                $activeCategory[] = $preparingLink;
            }
        }

        return $activeCategory;
    }

    /**
     * @return array
     */
    private function getActiveProducts(): array
    {
        $activeProduct = [];
        $products = $this->doctrine
            ->getRepository(Product::class)
            ->findBy(['isDeleted' => false, 'isPublished' => true]);

        foreach ($products as $product) {
            $activeProduct[] = $this->generateUrl(
                'main_product_show',
                ['identifier' => $product->getSlug()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }

        return $activeProduct;
    }
}
